<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Transaction;
use App\Enums\PaymentProviderEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Services\Payment\Contracts\PaymentServiceInterface;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripeService implements PaymentServiceInterface
{
    private StripeClient $stripe;
    private string $currency;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
        $this->currency = config('services.stripe.currency', 'usd');
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
            'payment_provider' => PaymentProviderEnum::STRIPE,
            'payment_method' => $options['payment_method'] ?? 'card',
            'status' => TransactionStatusEnum::PENDING,
            'type' => TransactionTypeEnum::PAYMENT,
            'amount' => $order->total,
            'currency' => strtoupper($this->currency),
            'customer_email' => $order->user_email,
            'customer_name' => $order->user_name,
            'metadata' => $options['metadata'] ?? [],
        ]);

        try {
            // Convert amount to cents (Stripe expects amounts in smallest currency unit)
            $amount = $this->convertToStripeAmount($order->total);

            $paymentIntentData = [
                'amount' => $amount,
                'currency' => $this->currency,
                'metadata' => array_merge([
                    'transaction_id' => $transaction->transaction_id,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id,
                    'user_name' => $order->user_name,
                    'user_email' => $order->user_email,
                ], $options['metadata'] ?? []),
                'description' => "Payment for Order #{$order->order_number}",
            ];

            // Add customer email if available
            if ($order->user_email) {
                $paymentIntentData['receipt_email'] = $order->user_email;
            }

            $paymentIntent = $this->stripe->paymentIntents->create($paymentIntentData);

            // Update transaction with Stripe payment intent details
            $transaction->update([
                'external_id' => $paymentIntent->id,
                'gateway_transaction_id' => $paymentIntent->id,
                'gateway_response' => $paymentIntent->toArray(),
            ]);

            return $transaction;
        } catch (ApiErrorException $e) {
            // Mark transaction as failed
            $transaction->markAsFailed($e->getMessage());
            
            Log::error('Stripe PaymentIntent creation failed', [
                'transaction_id' => $transaction->transaction_id,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Retrieve a payment intent by ID
     *
     * @param string $paymentIntentId
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return $this->stripe->paymentIntents->retrieve($paymentIntentId);
    }

    /**
     * Confirm a payment intent
     *
     * @param string $paymentIntentId
     * @param array $confirmationData
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function confirmPaymentIntent(string $paymentIntentId, array $confirmationData = []): PaymentIntent
    {
        return $this->stripe->paymentIntents->confirm($paymentIntentId, $confirmationData);
    }

    /**
     * Cancel a payment intent
     *
     * @param string $paymentIntentId
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function cancelPaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return $this->stripe->paymentIntents->cancel($paymentIntentId);
    }

    /**
     * Create a customer in Stripe
     *
     * @param array $customerData
     * @return \Stripe\Customer
     * @throws ApiErrorException
     */
    public function createCustomer(array $customerData)
    {
        return $this->stripe->customers->create($customerData);
    }

    /**
     * Get publishable key for frontend
     *
     * @return string
     */
    public function getPublishableKey(): string
    {
        return config('services.stripe.public');
    }

    /**
     * Convert amount to Stripe format (cents)
     *
     * @param float $amount
     * @return int
     */
    private function convertToStripeAmount(float $amount): int
    {
        // Most currencies use 2 decimal places (cents)
        // For currencies like JPY that don't use decimal places, you might need to adjust this
        return (int) round($amount * 100);
    }

    /**
     * Convert amount from Stripe format to regular amount
     *
     * @param int $stripeAmount
     * @return float
     */
    public function convertFromStripeAmount(int $stripeAmount): float
    {
        return $stripeAmount / 100;
    }

    /**
     * Verify webhook signature
     *
     * @param string $payload
     * @param string $signature
     * @return \Stripe\Event
     * @throws \Stripe\Exception\SignatureVerificationException
     */
    public function verifyWebhook(string $payload, string $signature): \Stripe\Event
    {
        $webhookSecret = config('services.stripe.webhook_secret');
        
        return \Stripe\Webhook::constructEvent(
            $payload,
            $signature,
            $webhookSecret
        );
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
            throw new \Exception('Transaction has no external payment intent ID');
        }

        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($transaction->external_id);
            
            $result = [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
            ];


            
            return $result;
        } catch (ApiErrorException $e) {
            Log::error('Failed to process Stripe payment', [
                'transaction_id' => $transaction->transaction_id,
                'external_id' => $transaction->external_id,
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
            $paymentIntent = $this->stripe->paymentIntents->retrieve($externalId);
            
            return $paymentIntent->status === 'succeeded' && 
                   $paymentIntent->metadata['transaction_id'] === $transaction->transaction_id;
        } catch (ApiErrorException $e) {
            Log::error('Failed to verify Stripe payment', [
                'transaction_id' => $transaction->transaction_id,
                'external_id' => $externalId,
                'error' => $e->getMessage()
            ]);
            return false;
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
        // Extract payment method details from gateway response
        $paymentMethodDetails = [];
        if (isset($gatewayResponse['payment_method'])) {
            $paymentMethodDetails = $this->extractPaymentMethodDetails($gatewayResponse['payment_method']);
        }

        // Calculate fees if available
        $fee = null;
        $netAmount = null;
        if (isset($gatewayResponse['charges']['data'][0]['balance_transaction'])) {
            $balanceTransaction = $gatewayResponse['charges']['data'][0]['balance_transaction'];
            $fee = $this->convertFromStripeAmount($balanceTransaction['fee'] ?? 0);
            $netAmount = $this->convertFromStripeAmount($balanceTransaction['net'] ?? 0);
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
        $failureReason = $gatewayResponse['last_payment_error']['message'] ?? 'Payment failed';
        
        $transaction->markAsFailed($failureReason, [
            'gateway_response' => $gatewayResponse,
        ]);

        // Update order status
        $order = $transaction->order;
        if ($order) {
            $order->update([
                'status' => \App\Enums\OrderStatusEnum::FAILED->value,
            ]);

            Log::warning('Order payment failed via transaction', [
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
            // Process refund with Stripe
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $transaction->gateway_transaction_id,
                'amount' => $this->convertToStripeAmount($amount),
                'reason' => $reason ? 'requested_by_customer' : null,
                'metadata' => [
                    'original_transaction_id' => $transaction->transaction_id,
                    'refund_transaction_id' => $refundTransaction->transaction_id,
                    'reason' => $reason,
                ],
            ]);

            // Update refund transaction with Stripe details
            $refundTransaction->update([
                'external_id' => $refund->id,
                'gateway_transaction_id' => $refund->id,
                'gateway_response' => $refund->toArray(),
                'status' => $refund->status === 'succeeded' ? 
                    TransactionStatusEnum::COMPLETED : 
                    TransactionStatusEnum::PROCESSING,
            ]);

            if ($refund->status === 'succeeded') {
                $refundTransaction->markAsCompleted();
            }

            return $refundTransaction;
        } catch (ApiErrorException $e) {
            $refundTransaction->markAsFailed($e->getMessage());
            
            Log::error('Stripe refund failed', [
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
        $config = [
            'provider' => $this->getProviderName(),
            'publishable_key' => $this->getPublishableKey(),
            'currency' => strtoupper($this->currency),
        ];



        return $config;
    }

    /**
     * Get the payment provider name
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return PaymentProviderEnum::STRIPE->value;
    }

    /**
     * Extract payment method details from Stripe response
     *
     * @param array|string $paymentMethod
     * @return array
     */
    private function extractPaymentMethodDetails($paymentMethod): array
    {
        if (is_string($paymentMethod)) {
            try {
                $paymentMethod = $this->stripe->paymentMethods->retrieve($paymentMethod);
                $paymentMethod = $paymentMethod->toArray();
            } catch (ApiErrorException $e) {
                return ['error' => 'Could not retrieve payment method details'];
            }
        }

        $details = [
            'type' => $paymentMethod['type'] ?? 'unknown',
        ];

        switch ($paymentMethod['type'] ?? '') {
            case 'card':
                $card = $paymentMethod['card'] ?? [];
                $details['card'] = [
                    'brand' => $card['brand'] ?? 'unknown',
                    'last4' => $card['last4'] ?? 'xxxx',
                    'exp_month' => $card['exp_month'] ?? null,
                    'exp_year' => $card['exp_year'] ?? null,
                    'funding' => $card['funding'] ?? 'unknown',
                ];
                break;
            case 'sepa_debit':
                $sepa = $paymentMethod['sepa_debit'] ?? [];
                $details['sepa_debit'] = [
                    'last4' => $sepa['last4'] ?? 'xxxx',
                    'bank_code' => $sepa['bank_code'] ?? null,
                ];
                break;
            // Add more payment method types as needed
        }

        return $details;
    }
}
