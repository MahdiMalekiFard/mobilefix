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
                3 => 'Pay'
            ]"
        />

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
    padding: 1rem;
}

.summary-icon-wrapper {
    width: 35px;
    height: 35px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.order-summary-body {
    padding: 1rem;
}

.order-detail-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.order-detail-item:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.detail-icon {
    width: 28px;
    height: 28px;
    background: #f8f9fa;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    color: #6c757d;
    flex-shrink: 0;
    font-size: 0.9rem;
}

.detail-content {
    flex: 1;
}

.detail-label {
    color: #6c757d;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.detail-value {
    color: #333;
    font-weight: 500;
    margin-top: 0.15rem;
    font-size: 0.9rem;
}

.problems-list {
    margin-top: 0.25rem;
}

.problem-item {
    color: #666;
    margin-bottom: 0.15rem;
    font-size: 0.85rem;
}

.problem-item:last-child {
    margin-bottom: 0;
}

.order-total {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid #e9ecef;
}

.total-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-label {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
}

.total-amount {
    font-size: 1.3rem;
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