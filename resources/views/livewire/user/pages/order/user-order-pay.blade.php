<div class="container py-5">
    @if($errorMessage)
        <div class="alert alert-danger" role="alert">
            <strong>Error:</strong> {{ $errorMessage }}
            @if($paymentStatus === 'failed')
                <div class="mt-2">
                    <button wire:click="retryPayment" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-retry"></i> Retry Payment
                    </button>
                </div>
            @endif
        </div>
    @endif

    @if($order)
        <div class="row">
            <!-- Order Summary -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Order Number</small>
                            <div class="fw-bold">{{ $order->order_number }}</div>
                        </div>
                        
                        @if($order->tracking_code)
                            <div class="mb-3">
                                <small class="text-muted">Tracking Code</small>
                                <div class="fw-bold">{{ $order->tracking_code }}</div>
                            </div>
                        @endif

                        @if($order->device)
                            <div class="mb-3">
                                <small class="text-muted">Device</small>
                                <div>{{ $order->brand->title ?? 'N/A' }} {{ $order->device->title ?? 'N/A' }}</div>
                            </div>
                        @endif

                        @if($order->problems->count() > 0)
                            <div class="mb-3">
                                <small class="text-muted">Problems</small>
                                <ul class="list-unstyled mb-0">
                                    @foreach($order->problems as $problem)
                                        <li class="small">• {{ $problem->title }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($order->user_note)
                            <div class="mb-3">
                                <small class="text-muted">Note</small>
                                <div class="small">{{ $order->user_note }}</div>
                            </div>
                        @endif

                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">Total Amount</span>
                            <span class="h4 mb-0 text-primary">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="col-lg-8">
                @if($paymentStatus === 'completed')
                    <div class="card border-success">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                            <h3 class="text-success">Payment Successful!</h3>
                            <p class="text-muted">Your payment has been processed successfully.</p>
                            <a href="{{ route('user.order.show', $order->id) }}" class="btn btn-success">
                                <i class="fas fa-eye"></i> View Order Details
                            </a>
                        </div>
                    </div>
                @elseif(empty($availableProviders))
                    <div class="card border-warning">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                            <h4>No Payment Methods Available</h4>
                            <p class="text-muted">Please contact support to complete your payment.</p>
                        </div>
                    </div>
                @elseif(!$selectedProvider)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Select Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($availableProviders as $provider)
                                    <div class="col-md-6 mb-3">
                                        <div class="card payment-provider-card h-100" 
                                             style="cursor: pointer;" 
                                             wire:click="selectProvider('{{ $provider->value }}')">
                                            <div class="card-body text-center">
                                                <i class="{{ $provider->icon() }} fa-3x mb-3 text-primary"></i>
                                                <h5>{{ $provider->title() }}</h5>
                                                <p class="text-muted small">
                                                    @if($provider === App\Enums\PaymentProviderEnum::STRIPE)
                                                        Pay securely with your credit or debit card
                                                    @elseif($provider === App\Enums\PaymentProviderEnum::PAYPAL)
                                                        Pay with your PayPal account
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @elseif($currentTransaction && $paymentStatus === 'ready')
                    <!-- Payment Form based on selected provider -->
                    @if($selectedProvider === 'stripe')
                        @include('livewire.user.pages.order.partials.stripe-payment')
                    @elseif($selectedProvider === 'paypal')
                        @include('livewire.user.pages.order.partials.paypal-payment')
                    @endif
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted">Initializing payment...</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <h3>Order Not Found</h3>
            <p class="text-muted">The requested order could not be found or you don't have permission to access it.</p>
            <a href="{{ route('user.order.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .payment-provider-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transform: translateY(-1px);
        transition: all 0.15s ease-in-out;
    }
    
    .payment-provider-card {
        transition: all 0.15s ease-in-out;
        border: 2px solid #dee2e6;
    }
    
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
@endpush
