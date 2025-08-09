<?php

namespace App\Livewire\User\Pages\Order;

use App\Models\Order;
use App\Models\Transaction;
use App\Services\Payment\PaymentServiceFactory;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentProviderEnum;
use App\Enums\TransactionStatusEnum;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class UserOrderPay extends Component
{
    public ?Order $order = null;
    public ?Transaction $currentTransaction = null;
    public string $selectedProvider = '';
    public array $availableProviders = [];
    public array $paymentConfigs = [];
    public string $paymentStatus = 'pending';
    public string $errorMessage = '';
    public bool $isProcessing = false;

    protected $rules = [
        'order' => 'required|exists:orders,id',
    ];

    public function mount($orderId = null)
    {
        if ($orderId) {
            $this->loadOrder($orderId);
            $this->initializePaymentProviders();
            $this->checkExistingTransaction();
        }
    }

    public function loadOrder($orderId)
    {
        $this->order = Order::with(['user', 'address', 'paymentMethod', 'device', 'brand', 'problems'])
            ->where('id', $orderId)
            ->when(auth()->check(), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->first();

        if (!$this->order) {
            $this->errorMessage = 'Order not found or you do not have permission to view this order.';
            return;
        }

        // Check if order is in a payable state
        if (!in_array($this->order->status, [OrderStatusEnum::PENDING->value, OrderStatusEnum::COMPLETED->value, OrderStatusEnum::FAILED->value])) {
            $this->errorMessage = 'This order cannot be paid at this time.';
            return;
        }

        // Check if order is already paid
        if ($this->order->status === OrderStatusEnum::PAID->value) {
            $this->paymentStatus = 'completed';
            $this->errorMessage = 'This order has already been paid.';
            return;
        }
    }

    public function initializePaymentProviders()
    {
        try {
            // Get available payment providers
            $this->availableProviders = PaymentServiceFactory::getAvailableProviders();
            $this->paymentConfigs = PaymentServiceFactory::getFrontendConfigs();
            
            if (empty($this->availableProviders)) {
                $this->errorMessage = 'No payment methods are available at this time.';
                return;
            }

            // If only one provider is available, auto-select it
            if (count($this->availableProviders) === 1) {
                $this->selectedProvider = $this->availableProviders[0]->value;
                $this->createPaymentTransaction();
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Unable to initialize payment providers.';
            Log::error('Payment provider initialization failed', [
                'order_id' => $this->order?->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function checkExistingTransaction()
    {
        if (!$this->order) {
            return;
        }

        // Check for pending transactions
        $this->currentTransaction = $this->order->transactions()
            ->whereIn('status', [TransactionStatusEnum::PENDING, TransactionStatusEnum::PROCESSING])
            ->latest()
            ->first();

        if ($this->currentTransaction) {
            $this->selectedProvider = $this->currentTransaction->payment_provider->value;
            $this->paymentStatus = 'ready';
        }
    }

    public function selectProvider($provider)
    {
        if (!in_array(PaymentProviderEnum::from($provider), $this->availableProviders)) {
            $this->errorMessage = 'Selected payment provider is not available.';
            return;
        }

        $this->selectedProvider = $provider;
        $this->errorMessage = '';
        $this->createPaymentTransaction();
    }

    public function createPaymentTransaction()
    {
        if (!$this->order || $this->errorMessage) {
            return;
        }

        try {
            $provider = PaymentProviderEnum::from($this->selectedProvider);
            $paymentService = PaymentServiceFactory::create($provider);
            
            // Cancel any existing pending transactions
            $this->order->transactions()
                ->pending()
                ->each(function ($transaction) {
                    $transaction->markAsCancelled('New payment attempt started');
                });

            // Create new transaction
            $this->currentTransaction = $paymentService->createPayment($this->order);
            $this->paymentStatus = 'ready';

        } catch (\Exception $e) {
            $this->errorMessage = 'Unable to initialize payment. Please try again.';
            Log::error('Payment transaction creation failed', [
                'order_id' => $this->order->id,
                'provider' => $this->selectedProvider,
                'error' => $e->getMessage()
            ]);
        }
    }

    #[On('payment-succeeded')]
    public function handlePaymentSuccess($externalId, $provider = null)
    {
        $this->isProcessing = true;
        
        try {
            if (!$this->currentTransaction) {
                throw new \Exception('No active transaction found');
            }

            $paymentService = PaymentServiceFactory::create($this->currentTransaction->payment_provider);
            
            if ($paymentService->verifyPayment($this->currentTransaction, $externalId)) {
                // Get updated gateway response and handle success
                if ($this->currentTransaction->payment_provider === PaymentProviderEnum::STRIPE) {
                    $stripeService = $paymentService;
                    $paymentIntent = $stripeService->retrievePaymentIntent($externalId);
                    $stripeService->handleSuccessfulPayment($this->currentTransaction, $paymentIntent->toArray());
                } elseif ($this->currentTransaction->payment_provider === PaymentProviderEnum::PAYPAL) {
                    // For PayPal, capture the order
                    $paypalService = $paymentService;
                    $result = $paypalService->captureOrder($this->currentTransaction);
                    if (!$result['success']) {
                        throw new \Exception($result['error']);
                    }
                }
                
                $this->paymentStatus = 'completed';
                $this->order->refresh();
                
                session()->flash('success', 'Payment completed successfully!');
                
                // Redirect to order details or success page
                $this->redirect(route('user.order.show', $this->order->id));
            } else {
                $this->errorMessage = 'Payment verification failed.';
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Error verifying payment. Please contact support.';
            Log::error('Payment verification failed', [
                'order_id' => $this->order->id,
                'transaction_id' => $this->currentTransaction?->transaction_id,
                'external_id' => $externalId,
                'error' => $e->getMessage()
            ]);
        }
        
        $this->isProcessing = false;
    }

    #[On('payment-failed')]
    public function handlePaymentFailure($error, $provider = null)
    {
        $this->isProcessing = false;
        $this->paymentStatus = 'failed';
        $this->errorMessage = $error['message'] ?? 'Payment failed. Please try again.';
        
        if ($this->currentTransaction) {
            try {
                $paymentService = PaymentServiceFactory::create($this->currentTransaction->payment_provider);
                $paymentService->handleFailedPayment($this->currentTransaction, $error);
            } catch (\Exception $e) {
                Log::error('Error handling payment failure', [
                    'transaction_id' => $this->currentTransaction->transaction_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::warning('Payment failed on frontend', [
            'order_id' => $this->order->id,
            'transaction_id' => $this->currentTransaction?->transaction_id,
            'error' => $error
        ]);
    }

    public function retryPayment()
    {
        $this->errorMessage = '';
        $this->paymentStatus = 'pending';
        $this->currentTransaction = null;
        $this->createPaymentTransaction();
    }

    public function getPaymentData()
    {
        if (!$this->currentTransaction) {
            return null;
        }

        try {
            $paymentService = PaymentServiceFactory::create($this->currentTransaction->payment_provider);
            return $paymentService->processPayment($this->currentTransaction);
        } catch (\Exception $e) {
            Log::error('Failed to get payment data', [
                'transaction_id' => $this->currentTransaction->transaction_id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function render()
    {
        return view('livewire.user.pages.order.user-order-pay', [
            'paymentData' => $this->getPaymentData(),
        ])->layout('components.layouts.user_panel');
    }
}
