<?php

namespace App\Livewire\User\Pages\Order;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Address;
use App\Services\Payment\PaymentServiceFactory;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentProviderEnum;
use App\Enums\TransactionStatusEnum;
use Livewire\Component;
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
    
    // Multi-step checkout properties
    public int $currentStep = 1;
    public array $steps = [
        1 => 'address',
        2 => 'payment',
        3 => 'confirm'
    ];
    public ?Address $selectedAddress = null;
    public array $userAddresses = [];
    public bool $showPaymentModal = false;

    protected $rules = [
        'order' => 'required|exists:orders,id',
    ];

    public function mount($order = null)
    {
        if ($order) {
            $this->loadOrder($order->id);
            $this->loadUserAddresses();
            $this->initializePaymentProviders();
            $this->checkExistingTransaction();
            $this->determineInitialStep();
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
        if (!in_array($this->order->status, [OrderStatusEnum::COMPLETED->value])) {
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

    public function loadUserAddresses()
    {
        $this->userAddresses = Address::where('user_id', auth()->id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
            
        // Auto-select the default address if available
        $defaultAddress = collect($this->userAddresses)->where('is_default', 1)->first();
        if ($defaultAddress) {
            $this->selectedAddress = Address::find($defaultAddress['id']);
        }
    }
    
    public function determineInitialStep()
    {
        // If user has no addresses, start at step 1 (address)
        if (empty($this->userAddresses)) {
            $this->currentStep = 1;
            return;
        }
        
        // If no address is selected, start at step 1
        if (!$this->selectedAddress) {
            $this->currentStep = 1;
            return;
        }
        
        // If address is selected but no payment provider, go to step 2
        if (!$this->selectedProvider) {
            $this->currentStep = 2;
            return;
        }
        
        // If everything is ready, go to step 3
        $this->currentStep = 3;
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

    public function handlePaymentSuccess($externalId, $provider = null)
    {
        // Prevent duplicate processing
        if ($this->isProcessing || $this->paymentStatus === 'completed') {
            return;
        }
        
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
                

                
                // Use redirectRoute to avoid Livewire routing conflicts
                $this->redirectRoute('user.order.show', ['order' => $this->order->id], navigate: true);
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

    public function handlePaymentFailure($message, $type = null, $code = null, $provider = null)
    {
        $this->isProcessing = false;
        $this->paymentStatus = 'failed';
        $this->errorMessage = $message;
        
        if ($this->currentTransaction) {
            try {
                $paymentService = PaymentServiceFactory::create($this->currentTransaction->payment_provider);
                $paymentService->handleFailedPayment($this->currentTransaction, [
                    'message' => $message,
                    'type' => $type,
                    'code' => $code
                ]);
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
            'error' => [
                'message' => $message,
                'type' => $type,
                'code' => $code
            ]
        ]);
    }

    public function retryPayment()
    {
        $this->errorMessage = '';
        $this->paymentStatus = 'pending';
        $this->currentTransaction = null;
        $this->createPaymentTransaction();
    }

    public function processPayment()
    {
        // This method can be called via wire:click for simple payment processing
        // For now, it just ensures the transaction is ready
        if (!$this->currentTransaction) {
            $this->createPaymentTransaction();
        }
    }
    
    // Multi-step checkout methods
    public function selectAddress($addressId)
    {
        $this->selectedAddress = Address::where('user_id', auth()->id())
            ->where('id', $addressId)
            ->first();
            
        if (!$this->selectedAddress) {
            $this->errorMessage = 'Address not found or access denied.';
            return;
        }
        
        $this->errorMessage = '';
    }
    
    public function continueToPayment()
    {
        if (!$this->selectedAddress) {
            $this->errorMessage = 'Please select an address to continue.';
            return;
        }
        
        $this->currentStep = 2;
        $this->errorMessage = '';
    }
    
    public function selectPaymentProvider($provider)
    {
        $this->selectProvider($provider);
        if (!$this->errorMessage) {
            $this->currentStep = 3;
        }
    }
    
    public function openPaymentModal()
    {
        if (!$this->selectedAddress) {
            $this->errorMessage = 'Please select an address first.';
            return;
        }
        
        if (!$this->selectedProvider) {
            $this->errorMessage = 'Please select a payment method first.';
            return;
        }
        
        if (!$this->currentTransaction) {
            $this->createPaymentTransaction();
        }
        
        $this->showPaymentModal = true;
        $this->errorMessage = '';
    }
    
    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
    }
    
    public function goToStep($step)
    {
        if ($step < 1 || $step > 3) {
            return;
        }
        
        // Validate previous steps before allowing navigation
        if ($step >= 2 && !$this->selectedAddress) {
            $this->errorMessage = 'Please select an address first.';
            return;
        }
        
        if ($step >= 3 && !$this->selectedProvider) {
            $this->errorMessage = 'Please select a payment method first.';
            return;
        }
        
        $this->currentStep = $step;
        $this->errorMessage = '';
    }
    
    public function getStepTitle($step)
    {
        return match($step) {
            1 => 'Select Address',
            2 => 'Choose Payment Method', 
            3 => 'Review & Pay',
            default => 'Step ' . $step
        };
    }
    
    public function isStepCompleted($step)
    {
        return match($step) {
            1 => !is_null($this->selectedAddress),
            2 => !is_null($this->selectedProvider),
            3 => $this->paymentStatus === 'completed',
            default => false
        };
    }
    
    public function isStepAccessible($step)
    {
        return match($step) {
            1 => true,
            2 => !is_null($this->selectedAddress),
            3 => !is_null($this->selectedAddress) && !is_null($this->selectedProvider),
            default => false
        };
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
