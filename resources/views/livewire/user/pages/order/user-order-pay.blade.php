<div class="container py-5">
    <!-- Error Messages -->
    @if($errorMessage)
        <div class="alert alert-danger mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Error:</strong> {{ $errorMessage }}
                    @if($paymentStatus === 'failed')
                        <div class="mt-2">
                            <button wire:click="retryPayment" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-redo"></i> Retry Payment
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($order)
        <!-- Checkout Stepper -->
        <x-checkout-stepper 
            :currentStep="$currentStep"
            :steps="$steps"
            :isStepCompleted="fn($step) => $this->isStepCompleted($step)"
            :isStepAccessible="fn($step) => $this->isStepAccessible($step)"
            :stepTitles="[
                1 => 'Select Address',
                2 => 'Choose Payment',
                3 => 'Review & Pay'
            ]"
        />

        @if($currentStep === 3)
            <!-- Two Column Layout for Final Step -->
            <div class="row">
                <!-- Order Summary (Only on Step 3) -->
                <div class="col-lg-4 mb-4">
                    <div class="card order-summary-card shadow-lg border-0">
                        <div class="card-header order-summary-header">
                            <div class="d-flex align-items-center">
                                <div class="summary-icon-wrapper me-3">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-white">Order Summary</h5>
                                    <small class="text-white opacity-75">Order #{{ $order->order_number }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body order-summary-body">
                            <!-- Device Info -->
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

                            <!-- Services -->
                            @if($order->problems->count() > 0)
                                <div class="order-detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="detail-content">
                                        <small class="detail-label">Services</small>
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

                            <!-- Selected Address -->
                            @if($selectedAddress)
                                <div class="order-detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="detail-content">
                                        <small class="detail-label">Delivery Address</small>
                                        <div class="detail-value">
                                            <strong>{{ $selectedAddress->title }}</strong><br>
                                            {{ $selectedAddress->address }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Selected Payment Method -->
                            @if($selectedProvider)
                                <div class="order-detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="detail-content">
                                        <small class="detail-label">Payment Method</small>
                                        <div class="detail-value">
                                            {{ App\Enums\PaymentProviderEnum::from($selectedProvider)->title() }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Order Total -->
                            <div class="order-total">
                                <div class="total-line">
                                    <span class="total-label">Total Amount</span>
                                    <span class="total-amount">${{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="col-lg-8">
        @else
            <!-- Single Column Layout for Steps 1 & 2 -->
            <div class="row">
                <div class="col-12">
        @endif
                    @if($paymentStatus === 'completed')
                    <!-- Success State -->
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
                @else
                    <!-- Step Content -->
                    @if($currentStep === 1)
                        <!-- Step 1: Address Selection -->
                        @include('livewire.user.pages.order.steps.address-selection')
                    @elseif($currentStep === 2)
                        <!-- Step 2: Payment Method Selection -->
                        @include('livewire.user.pages.order.steps.payment-selection')
                    @elseif($currentStep === 3)
                        <!-- Step 3: Review and Pay -->
                        @include('livewire.user.pages.order.steps.review-and-pay')
                    @endif
                @endif
                </div>
            </div>
    @else
        <!-- No Order State -->
        <div class="card border-danger">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                <h4>Order Not Found</h4>
                <p class="text-muted">The order you're looking for doesn't exist or you don't have permission to view it.</p>
                <a href="{{ route('user.order.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    @endif

    <!-- Payment Modal -->
    @if($showPaymentModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="{{ App\Enums\PaymentProviderEnum::from($selectedProvider)->icon() }} me-2"></i>
                            Complete Payment
                        </h5>
                        <button type="button" class="btn-close" wire:click="closePaymentModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($selectedProvider === 'stripe')
                            @include('livewire.user.pages.order.partials.stripe-payment')
                        @elseif($selectedProvider === 'paypal')
                            @include('livewire.user.pages.order.partials.paypal-payment')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.order-summary-card {
    border-radius: 12px;
    overflow: hidden;
}

.order-summary-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 1.5rem;
}

.summary-icon-wrapper {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.order-summary-body {
    padding: 1.5rem;
}

.order-detail-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.order-detail-item:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: #6c757d;
    flex-shrink: 0;
}

.detail-content {
    flex: 1;
}

.detail-label {
    color: #6c757d;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.detail-value {
    color: #333;
    font-weight: 500;
    margin-top: 0.25rem;
}

.problems-list {
    margin-top: 0.5rem;
}

.problem-item {
    color: #666;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.problem-item:last-child {
    margin-bottom: 0;
}

.order-total {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 2px solid #e9ecef;
}

.total-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.total-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #28a745;
}

.step-card {
    border-radius: 12px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.step-card:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.step-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem;
    border-radius: 12px 12px 0 0;
}

.step-title {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.step-body {
    padding: 1.5rem;
}
</style>
@endpush