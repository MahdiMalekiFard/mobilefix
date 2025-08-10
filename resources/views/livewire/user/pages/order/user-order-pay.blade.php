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
                <div class="card order-summary-card shadow-lg border-0">
                    <div class="card-header order-summary-header">
                        <div class="d-flex align-items-center">
                            <div class="summary-icon-wrapper me-3">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">Order Summary</h5>
                                <small class="text-white opacity-75">Order Details</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body order-summary-body">
                        <!-- Order Number -->
                        <div class="order-detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div class="detail-content">
                                <small class="detail-label">Order Number</small>
                                <div class="detail-value">{{ $order->order_number }}</div>
                            </div>
                        </div>
                        
                        @if($order->tracking_code)
                            <div class="order-detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="detail-content">
                                    <small class="detail-label">Tracking Code</small>
                                    <div class="detail-value">{{ $order->tracking_code }}</div>
                                </div>
                            </div>
                        @endif

                        @if($order->device)
                            <div class="order-detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <small class="detail-label">Device</small>
                                    <div class="detail-value">{{ $order->brand->title ?? 'N/A' }} {{ $order->device->title ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @endif

                        @if($order->problems->count() > 0)
                            <div class="order-detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="detail-content">
                                    <small class="detail-label">Services Required</small>
                                    <div class="problems-list">
                                        @foreach($order->problems as $problem)
                                            <div class="problem-item">
                                                <i class="fas fa-dot-circle me-2"></i>
                                                {{ $problem->title }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($order->user_note)
                            <div class="order-detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                                <div class="detail-content">
                                    <small class="detail-label">Additional Notes</small>
                                    <div class="detail-value note-content">{{ $order->user_note }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Total Amount Section -->
                        <div class="total-amount-section">
                            <div class="amount-wrapper">
                                <div class="amount-label">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Total Amount
                                </div>
                                <div class="amount-value">
                                    ${{ number_format($order->total, 2) }}
                                </div>
                            </div>
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
    /* Order Summary Card Styling */
    .order-summary-card {
        border-radius: 1.25rem;
        overflow: hidden;
        transition: all 0.3s ease;
        animation: slideInUp 0.6s ease-out;
    }
    
    .order-summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Order Summary Header */
    .order-summary-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 1.5rem;
        position: relative;
    }
    
    .order-summary-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1), transparent);
        pointer-events: none;
    }
    
    .summary-icon-wrapper {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .summary-icon-wrapper i {
        font-size: 1.25rem;
        color: white;
    }
    
    /* Order Summary Body */
    .order-summary-body {
        padding: 2rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
    }
    
    /* Order Detail Items */
    .order-detail-item {
        display: flex;
        align-items: flex-start;
        padding: 1rem;
        margin-bottom: 1rem;
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .order-detail-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: linear-gradient(180deg, #667eea, #764ba2);
        border-radius: 0 2px 2px 0;
    }
    
    .order-detail-item:hover {
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        transform: translateX(5px);
    }
    
    .detail-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(45deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .detail-icon i {
        color: white;
        font-size: 1rem;
    }
    
    .detail-content {
        flex: 1;
    }
    
    .detail-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .detail-value {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
        line-height: 1.4;
    }
    
    /* Problems List Styling */
    .problems-list {
        margin-top: 0.5rem;
    }
    
    .problem-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
        font-size: 0.9rem;
        color: #495057;
        border-bottom: 1px dashed #dee2e6;
    }
    
    .problem-item:last-child {
        border-bottom: none;
    }
    
    .problem-item i {
        color: #667eea;
        font-size: 0.7rem;
    }
    
    /* Note Content */
    .note-content {
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 0.5rem;
        border-left: 3px solid #667eea;
        font-style: italic;
        margin-top: 0.5rem;
    }
    
    /* Total Amount Section */
    .total-amount-section {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px dashed #dee2e6;
    }
    
    .amount-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1.5rem;
        border-radius: 1rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .amount-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 3s infinite;
    }
    
    .amount-label {
        color: white;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        opacity: 0.9;
    }
    
    .amount-value {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 1;
    }
    
    /* Payment Provider Cards */
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
    
    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes shimmer {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
        .order-summary-body {
            padding: 1.5rem;
        }
        
        .order-detail-item {
            padding: 0.75rem;
        }
        
        .amount-wrapper {
            padding: 1.25rem;
        }
        
        .amount-value {
            font-size: 1.75rem;
        }
    }
    
    @media (max-width: 768px) {
        .order-summary-header {
            padding: 1.25rem;
        }
        
        .order-summary-body {
            padding: 1.25rem;
        }
        
        .order-detail-item {
            flex-direction: column;
            text-align: center;
            padding: 1rem;
        }
        
        .detail-icon {
            margin: 0 auto 0.75rem auto;
        }
        
        .summary-icon-wrapper {
            width: 40px;
            height: 40px;
        }
        
        .amount-value {
            font-size: 1.5rem;
        }
    }
    
    /* Loading State */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
@endpush
