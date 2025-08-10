<div class="card step-card">
    <div class="step-header">
        <h4 class="step-title">
            <i class="fas fa-eye me-2"></i>
            Review & Complete Payment
        </h4>
        <p class="text-muted mb-0">Review your order details and complete the payment</p>
    </div>
    <div class="step-body">
        <!-- Order Review Section -->
        <div class="review-section mb-4">
            <h5 class="section-title">
                <i class="fas fa-clipboard-list me-2"></i>
                Order Review
            </h5>
            
            <div class="review-grid">
                <!-- Device & Service Info -->
                <div class="review-item">
                    <div class="review-label">Device & Service</div>
                    <div class="review-content">
                        <div class="device-info">
                            <strong>{{ $order->brand->title ?? 'N/A' }} {{ $order->device->title ?? 'N/A' }}</strong>
                            @if($order->problems->count() > 0)
                                <div class="services-list mt-2">
                                    @foreach($order->problems as $problem)
                                        <span class="service-tag">{{ $problem->title }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="review-item">
                    <div class="review-label">Delivery Address</div>
                    <div class="review-content">
                        @if($selectedAddress)
                            <div class="address-info">
                                <strong>{{ $selectedAddress->title }}</strong><br>
                                {{ $selectedAddress->address }}
                            </div>
                        @endif
                    </div>
                    <div class="review-action">
                        <button wire:click="goToStep(1)" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Change
                        </button>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="review-item">
                    <div class="review-label">Payment Method</div>
                    <div class="review-content">
                        @if($selectedProvider)
                            <div class="payment-info">
                                <i class="{{ App\Enums\PaymentProviderEnum::from($selectedProvider)->icon() }} me-2"></i>
                                <strong>{{ App\Enums\PaymentProviderEnum::from($selectedProvider)->title() }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="review-action">
                        <button wire:click="goToStep(2)" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Change
                        </button>
                    </div>
                </div>

                <!-- Order Total -->
                <div class="review-item total-item">
                    <div class="review-label">Total Amount</div>
                    <div class="review-content">
                        <div class="total-display">
                            <span class="currency">$</span>
                            <span class="amount">{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="terms-section mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="termsAgreement" checked>
                <label class="form-check-label" for="termsAgreement">
                    I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                </label>
            </div>
        </div>

        <!-- Payment Actions -->
        <div class="payment-actions">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <button wire:click="goToStep(2)" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Payment Methods
                    </button>
                </div>
                <div class="col-md-6 mb-3">
                    <button wire:click="openPaymentModal" 
                            class="btn btn-success btn-lg w-100 payment-button"
                            @if($isProcessing) disabled @endif>
                        @if($isProcessing)
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Processing...
                        @else
                            <i class="fas fa-credit-card me-2"></i>
                            Pay ${{ number_format($order->total, 2) }}
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="security-notice mt-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-shield-alt text-success me-3"></i>
                <div>
                    <small class="text-muted">
                        <strong>Secure Payment:</strong> Your payment information is encrypted and processed securely. 
                        We never store your payment details.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.review-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
}

.section-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.review-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.review-item {
    display: grid;
    grid-template-columns: 150px 1fr auto;
    gap: 1rem;
    align-items: start;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.review-item.total-item {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    font-weight: 600;
}

.review-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.total-item .review-label {
    color: rgba(255, 255, 255, 0.9);
}

.review-content {
    color: #333;
}

.total-item .review-content {
    color: white;
}

.device-info strong {
    color: #333;
    font-size: 1.1rem;
}

.services-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.service-tag {
    background: #e3f2fd;
    color: #1976d2;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.address-info {
    line-height: 1.5;
}

.payment-info {
    display: flex;
    align-items: center;
    font-size: 1.1rem;
}

.total-display {
    font-size: 1.5rem;
    font-weight: 700;
}

.currency {
    font-size: 1.2rem;
    opacity: 0.9;
}

.amount {
    font-size: 1.8rem;
}

.review-action {
    display: flex;
    align-items: center;
}

.terms-section {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 1rem;
}

.form-check-label {
    font-size: 0.9rem;
    color: #856404;
}

.payment-actions {
    border-top: 2px solid #e9ecef;
    padding-top: 1.5rem;
}

.payment-button {
    font-size: 1.1rem;
    font-weight: 600;
    padding: 1rem 2rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.payment-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

.security-notice {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    border-radius: 8px;
    padding: 1rem;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .review-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: left;
    }
    
    .review-action {
        justify-content: flex-start;
        margin-top: 0.5rem;
    }
    
    .services-list {
        justify-content: flex-start;
    }
    
    .total-display {
        font-size: 1.3rem;
    }
    
    .amount {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .review-section {
        padding: 1rem;
    }
    
    .payment-actions .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .payment-button {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }
}
</style>
@endpush
