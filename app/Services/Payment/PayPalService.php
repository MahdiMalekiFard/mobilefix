<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Transaction;
use App\Enums\PaymentProviderEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Services\Payment\Contracts\PaymentServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PayPalService implements PaymentServiceInterface
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $currency;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->baseUrl = config('services.paypal.sandbox') 
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
        $this->currency = config('services.paypal.currency', 'EUR');
    }

    /**
     * Create a payment transaction for an order
     *
     * @param Order $order
     * @param array $options
     * @return Transaction
     */
    public function createPayment(Order $order, array $options = []): Transaction
    {
        // Create transaction record first
        $transaction = Transaction::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_provider' => PaymentProviderEnum::PAYPAL,
            'payment_method' => $options['payment_method'] ?? 'paypal',
            'status' => TransactionStatusEnum::PENDING,
            'type' => TransactionTypeEnum::PAYMENT,
            'amount' => $order->total,
            'currency' => strtoupper($this->currency),
            'customer_email' => $order->user_email,
            'customer_name' => $order->user_name,
            'metadata' => $options['metadata'] ?? [],
        ]);

        try {
            $accessToken = $this->getAccessToken();
            
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $transaction->transaction_id,
                    'amount' => [
                        'currency_code' => strtoupper($this->currency),
                        'value' => number_format($order->total, 2, '.', ''),
                    ],
                    'description' => "Payment for Order #{$order->order_number}",
                    'custom_id' => $transaction->transaction_id,
                ]],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'landing_page' => 'NO_PREFERENCE',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('payment.paypal.success'),
                    'cancel_url' => route('payment.paypal.cancel'),
                ],
            ];

            $response = Http::withToken($accessToken)
                ->post($this->baseUrl . '/v2/checkout/orders', $orderData);

            if ($response->successful()) {
                $paypalOrder = $response->json();
                
                // Update transaction with PayPal order details
                $transaction->update([
                    'external_id' => $paypalOrder['id'],
                    'gateway_transaction_id' => $paypalOrder['id'],
                    'gateway_response' => $paypalOrder,
                ]);

                return $transaction;
            } else {
                throw new \Exception('PayPal API error: ' . $response->body());
            }
        } catch (\Exception $e) {
            // Mark transaction as failed
            $transaction->markAsFailed($e->getMessage());
            
            Log::error('PayPal order creation failed', [
                'transaction_id' => $transaction->transaction_id,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Process a payment for the given transaction
     *
     * @param Transaction $transaction
     * @param array $paymentData
     * @return array
     */
    public function processPayment(Transaction $transaction, array $paymentData = []): array
    {
        if (!$transaction->external_id) {
            throw new \Exception('Transaction has no external PayPal order ID');
        }

        try {
            $accessToken = $this->getAccessToken();
            $response = Http::withToken($accessToken)
                ->get($this->baseUrl . "/v2/checkout/orders/{$transaction->external_id}");

            if ($response->successful()) {
                $paypalOrder = $response->json();
                
                // Find approval URL
                $approvalUrl = null;
                foreach ($paypalOrder['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }

                return [
                    'success' => true,
                    'approval_url' => $approvalUrl,
                    'order_id' => $paypalOrder['id'],
                    'status' => $paypalOrder['status'],
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to retrieve PayPal order',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to process PayPal payment', [
                'transaction_id' => $transaction->transaction_id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a payment transaction
     *
     * @param Transaction $transaction
     * @param string $externalId
     * @return bool
     */
    public function verifyPayment(Transaction $transaction, string $externalId): bool
    {
        try {
            $accessToken = $this->getAccessToken();
            $response = Http::withToken($accessToken)
                ->get($this->baseUrl . "/v2/checkout/orders/{$externalId}");

            if ($response->successful()) {
                $paypalOrder = $response->json();
                
                return $paypalOrder['status'] === 'COMPLETED' && 
                       $paypalOrder['purchase_units'][0]['custom_id'] === $transaction->transaction_id;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to verify PayPal payment', [
                'transaction_id' => $transaction->transaction_id,
                'external_id' => $externalId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Capture PayPal order
     *
     * @param Transaction $transaction
     * @return array
     */
    public function captureOrder(Transaction $transaction): array
    {
        try {
            $accessToken = $this->getAccessToken();
            
            $response = Http::withToken($accessToken)
                ->post($this->baseUrl . "/v2/checkout/orders/{$transaction->external_id}/capture");

            if ($response->successful()) {
                $captureData = $response->json();
                
                if ($captureData['status'] === 'COMPLETED') {
                    $this->handleSuccessfulPayment($transaction, $captureData);
                }

                return [
                    'success' => true,
                    'capture_data' => $captureData,
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to capture PayPal payment',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to capture PayPal order', [
                'transaction_id' => $transaction->transaction_id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle successful payment callback
     *
     * @param Transaction $transaction
     * @param array $gatewayResponse
     * @return void
     */
    public function handleSuccessfulPayment(Transaction $transaction, array $gatewayResponse = []): void
    {
        // Extract payment method details
        $paymentMethodDetails = [
            'type' => 'paypal',
            'payer_email' => $gatewayResponse['payer']['email_address'] ?? null,
            'payer_id' => $gatewayResponse['payer']['payer_id'] ?? null,
        ];

        // Extract fee information if available
        $fee = null;
        $netAmount = null;
        if (isset($gatewayResponse['purchase_units'][0]['payments']['captures'][0])) {
            $capture = $gatewayResponse['purchase_units'][0]['payments']['captures'][0];
            if (isset($capture['seller_receivable_breakdown'])) {
                $breakdown = $capture['seller_receivable_breakdown'];
                $fee = floatval($breakdown['paypal_fee']['value'] ?? 0);
                $netAmount = floatval($breakdown['net_amount']['value'] ?? 0);
            }
        }

        // Update transaction
        $transaction->markAsCompleted([
            'gateway_response' => $gatewayResponse,
            'gateway_transaction_id' => $gatewayResponse['id'] ?? $transaction->gateway_transaction_id,
            'payment_method_details' => $paymentMethodDetails,
            'fee' => $fee,
            'net_amount' => $netAmount,
        ]);

        // Update order status
        $order = $transaction->order;
        if ($order) {
            $order->update([
                'status' => \App\Enums\OrderStatusEnum::PAID->value,
            ]);

            Log::info('Order payment completed via PayPal transaction', [
                'order_id' => $order->id,
                'transaction_id' => $transaction->transaction_id,
                'paypal_order_id' => $gatewayResponse['id'] ?? null
            ]);
        }
    }

    /**
     * Handle failed payment callback
     *
     * @param Transaction $transaction
     * @param array $gatewayResponse
     * @return void
     */
    public function handleFailedPayment(Transaction $transaction, array $gatewayResponse = []): void
    {
        $failureReason = $gatewayResponse['details'][0]['description'] ?? 'PayPal payment failed';
        
        $transaction->markAsFailed($failureReason, [
            'gateway_response' => $gatewayResponse,
        ]);

        // Update order status
        $order = $transaction->order;
        if ($order) {
            $order->update([
                'status' => \App\Enums\OrderStatusEnum::FAILED->value,
            ]);

            Log::warning('Order payment failed via PayPal transaction', [
                'order_id' => $order->id,
                'transaction_id' => $transaction->transaction_id,
                'error' => $failureReason
            ]);
        }
    }

    /**
     * Process a refund for the given transaction
     *
     * @param Transaction $transaction
     * @param float $amount
     * @param string|null $reason
     * @return Transaction
     */
    public function processRefund(Transaction $transaction, float $amount, ?string $reason = null): Transaction
    {
        if (!$transaction->canBeRefunded()) {
            throw new \Exception('Transaction cannot be refunded');
        }

        // Create refund transaction
        $refundTransaction = $transaction->createRefund($amount, $reason);

        try {
            $accessToken = $this->getAccessToken();
            
            // Get capture ID from original transaction
            $captureId = $this->getCaptureIdFromTransaction($transaction);
            
            $refundData = [
                'amount' => [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency_code' => strtoupper($this->currency),
                ],
                'note_to_payer' => $reason ?? 'Refund processed',
            ];

            $response = Http::withToken($accessToken)
                ->post($this->baseUrl . "/v2/payments/captures/{$captureId}/refund", $refundData);

            if ($response->successful()) {
                $refundData = $response->json();
                
                // Update refund transaction with PayPal details
                $refundTransaction->update([
                    'external_id' => $refundData['id'],
                    'gateway_transaction_id' => $refundData['id'],
                    'gateway_response' => $refundData,
                    'status' => $refundData['status'] === 'COMPLETED' ? 
                        TransactionStatusEnum::COMPLETED : 
                        TransactionStatusEnum::PROCESSING,
                ]);

                if ($refundData['status'] === 'COMPLETED') {
                    $refundTransaction->markAsCompleted();
                }

                return $refundTransaction;
            } else {
                throw new \Exception('PayPal refund failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            $refundTransaction->markAsFailed($e->getMessage());
            
            Log::error('PayPal refund failed', [
                'transaction_id' => $transaction->transaction_id,
                'refund_transaction_id' => $refundTransaction->transaction_id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get the configuration for the frontend
     *
     * @return array
     */
    public function getFrontendConfig(): array
    {
        return [
            'provider' => $this->getProviderName(),
            'client_id' => $this->clientId,
            'currency' => strtoupper($this->currency),
            'environment' => config('services.paypal.sandbox') ? 'sandbox' : 'production',
        ];
    }

    /**
     * Get the payment provider name
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return PaymentProviderEnum::PAYPAL->value;
    }

    /**
     * Get PayPal access token
     *
     * @return string
     */
    private function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post($this->baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Failed to get PayPal access token: ' . $response->body());
    }

    /**
     * Extract capture ID from transaction gateway response
     *
     * @param Transaction $transaction
     * @return string
     */
    private function getCaptureIdFromTransaction(Transaction $transaction): string
    {
        $gatewayResponse = $transaction->gateway_response;
        
        if (isset($gatewayResponse['purchase_units'][0]['payments']['captures'][0]['id'])) {
            return $gatewayResponse['purchase_units'][0]['payments']['captures'][0]['id'];
        }

        throw new \Exception('Capture ID not found in transaction gateway response');
    }
}
