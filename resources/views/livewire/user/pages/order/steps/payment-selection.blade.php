<div class="card step-card">
    <div class="step-header">
        <h4 class="step-title">
            <i class="fas fa-credit-card me-2"></i>
            Choose Payment Method
        </h4>
        <p class="text-muted mb-0">Select your preferred payment method</p>
    </div>
    <div class="step-body">
        @if(empty($availableProviders))
            <!-- No Payment Methods Available -->
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <h5>No Payment Methods Available</h5>
                <p class="text-muted">Please contact support to complete your payment.</p>
                <a href="mailto:support@example.com" class="btn btn-outline-primary">
                    <i class="fas fa-envelope"></i> Contact Support
                </a>
            </div>
        @else
            <!-- Payment Methods Grid -->
            <div class="row">
                @foreach($availableProviders as $provider)
                    <div class="col-md-6 mb-3">
                        <div class="payment-method-card {{ $selectedProvider === $provider->value ? 'selected' : '' }}"
                             wire:click="selectPaymentProvider('{{ $provider->value }}')"
                             style="cursor: pointer;">
                            <div class="payment-card-body text-center">
                                <!-- Provider Icon -->
                                <div class="payment-icon mb-3">
                                    <i class="{{ $provider->icon() }} fa-3x"></i>
                                </div>
                                
                                <!-- Provider Title -->
                                <h5 class="payment-title mb-2">{{ $provider->title() }}</h5>
                                
                                <!-- Provider Description -->
                                <p class="payment-description text-muted small mb-3">
                                    @if($provider === App\Enums\PaymentProviderEnum::STRIPE)
                                        Pay securely with your credit or debit card
                                    @elseif($provider === App\Enums\PaymentProviderEnum::PAYPAL)
                                        Pay with your PayPal account or credit card
                                    @else
                                        Secure payment processing
                                    @endif
                                </p>
                                
                                <!-- Security Features -->
                                <div class="security-features">
                                    <div class="security-item">
                                        <i class="fas fa-shield-alt text-success me-1"></i>
                                        <small class="text-muted">SSL Encrypted</small>
                                    </div>
                                    <div class="security-item">
                                        <i class="fas fa-lock text-success me-1"></i>
                                        <small class="text-muted">Secure</small>
                                    </div>
                                </div>
                                
                                <!-- Selection Indicator -->
                                @if($selectedProvider === $provider->value)
                                    <div class="selected-indicator">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payment Security Notice -->
            <div class="payment-security-notice mt-4">
                <div class="security-notice-card">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-info me-3 fa-2x"></i>
                        <div>
                            <h6 class="mb-1">Your payment is secure</h6>
                            <small class="text-muted">
                                We use industry-standard encryption to protect your payment information. 
                                Your payment details are processed securely and never stored on our servers.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="step-actions mt-4 d-flex justify-content-between">
                <button wire:click="goToStep(1)" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Address
                </button>
                
                @if($selectedProvider)
                    <button wire:click="goToStep(3)" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>
                        Review Order
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.payment-method-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.payment-method-card:hover {
    border-color: #007bff;
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
    transform: translateY(-5px);
}

.payment-method-card.selected {
    border-color: #28a745;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
    transform: translateY(-5px);
}

.payment-card-body {
    padding: 2rem 1.5rem;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.payment-icon {
    color: #6c757d;
    transition: color 0.3s ease;
}

.payment-method-card:hover .payment-icon {
    color: #007bff;
}

.payment-method-card.selected .payment-icon {
    color: #28a745;
}

.payment-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.payment-description {
    font-size: 0.9rem;
    line-height: 1.4;
}

.security-features {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

.security-item {
    display: flex;
    align-items: center;
    font-size: 0.8rem;
}

.selected-indicator {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 1.5rem;
}

.payment-security-notice {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem;
}

.security-notice-card {
    padding: 1rem;
}

.step-actions {
    border-top: 1px solid #e9ecef;
    padding-top: 1.5rem;
}

/* Stripe specific styling */
.payment-method-card:has(.fa-cc-stripe) .payment-icon {
    color: #635bff;
}

.payment-method-card.selected:has(.fa-cc-stripe) .payment-icon {
    color: #635bff;
}

/* PayPal specific styling */
.payment-method-card:has(.fa-paypal) .payment-icon {
    color: #0070ba;
}

.payment-method-card.selected:has(.fa-paypal) .payment-icon {
    color: #0070ba;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .payment-card-body {
        padding: 1.5rem 1rem;
    }
    
    .payment-icon {
        margin-bottom: 1rem;
    }
    
    .security-features {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .step-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .step-actions .btn {
        width: 100%;
    }
}
</style>
@endpush
