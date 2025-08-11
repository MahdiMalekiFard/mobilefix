<div class="stripe-payment-modal-content">
    <div class="card-body p-4">
        @if($paymentData && $paymentData['success'])
            <!-- Payment Amount Display -->


            <form id="stripe-payment-form">
                <!-- Card Information Section -->
                <div class="payment-section card-information-section mb-4">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <label class="section-label">
                            Card Information
                            <span class="section-required">*</span>
                        </label>
                    </div>
                    <div class="card-input-container">
                        <div class="card-input-wrapper">
                            <div id="stripe-card-element" class="stripe-element">
                                <!-- Stripe Elements will create form elements here -->
                            </div>
                        </div>
                        <div id="stripe-card-errors" role="alert" class="error-message mt-3"></div>
                    </div>
                </div>

                <!-- Save Card Option -->
                <div class="payment-section save-card-section mb-4">
                    <div class="custom-checkbox-wrapper">
                        <div class="checkbox-container">
                            <input class="custom-checkbox-input" type="checkbox" id="save-card" name="save-card">
                            <label class="custom-checkbox-label" for="save-card">
                                <span class="checkbox-indicator">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="checkbox-content">
                                    <div class="checkbox-title">
                                        <i class="fas fa-bookmark me-2"></i>
                                        Save this card for future payments
                                    </div>
                                    <div class="checkbox-description">
                                        Securely store your payment method for faster checkout
                                    </div>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Button -->
                <div class="payment-section payment-button-section mb-4">
                    <button type="submit" id="stripe-submit-payment" class="stripe-payment-button" {{ $isProcessing ? 'disabled' : '' }}>
                        <div class="button-background"></div>
                        <span id="stripe-button-text" class="button-content">
                            @if($isProcessing)
                                <div class="processing-state">
                                    <div class="processing-spinner">
                                        <div class="spinner-ring"></div>
                                    </div>
                                    <span class="processing-text">Processing Payment...</span>
                                </div>
                            @else
                                <div class="payment-state">
                                    <div class="payment-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="payment-text">
                                        <span class="action-text">Pay Securely</span>
                                        <span class="amount-text">${{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div class="payment-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </div>
                            @endif
                        </span>
                    </button>
                </div>
            </form>
        @else
            <div class="alert alert-danger border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fs-4 me-3 text-danger"></i>
                    <div>
                        <div class="fw-semibold">Payment Initialization Failed</div>
                        <div class="small">Unable to initialize Stripe payment. Please try again or contact support.</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if($paymentData && $paymentData['success'])
@push('styles')
<style>
    /* ===== STRIPE PAYMENT CARD STYLING ===== */
    
    /* Main Card */
    .stripe-payment-card {
        border-radius: 1.5rem;
        overflow: hidden;
        background: white;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        animation: slideInFromRight 0.8s ease-out;
    }
    
    .stripe-payment-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
    }
    
    /* Header Styling */
    .stripe-payment-header {
        position: relative;
        overflow: hidden;
        border: none;
        padding: 0;
        min-height: 120px;
    }
    
    .header-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
        opacity: 1;
    }
    
    .header-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="white" opacity="0.1"/><circle cx="25" cy="25" r="0.3" fill="white" opacity="0.05"/><circle cx="75" cy="75" r="0.4" fill="white" opacity="0.08"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        animation: float 6s ease-in-out infinite;
    }
    
    .header-content {
        position: relative;
        z-index: 2;
        padding: 2rem;
    }
    
    .payment-brand-wrapper {
        position: relative;
    }
    
    .stripe-logo {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(20px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .stripe-logo i {
        font-size: 1.5rem;
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }
    
    .payment-title h5 {
        font-size: 1.25rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .payment-subtitle {
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .change-method-btn {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        padding: 0.5rem 1rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .change-method-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
    }
    
    /* Payment Amount Display */
    .amount-card {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        background: white;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .amount-card:hover {
        border-color: #6366f1;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1);
    }
    
    .amount-card-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .amount-card-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        padding: 1.5rem;
        gap: 1rem;
    }
    
    .amount-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .amount-details {
        flex: 1;
    }
    
    .amount-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .amount-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .amount-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        gap: 0.75rem;
    }
    
    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .section-label {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }
    
    .section-required {
        color: #ef4444;
        margin-left: 0.25rem;
    }
    
    /* Card Input Styling */
    .card-input-container {
        position: relative;
    }
    
    .card-input-wrapper {
        position: relative;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card-input-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .card-input-wrapper:hover::before,
    .card-input-wrapper.focused::before {
        opacity: 1;
    }
    
    .card-input-wrapper:hover {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    .card-input-wrapper.focused {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }
    
    .card-input-wrapper.invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
    }
    
    .card-input-wrapper.complete {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }
    
    .stripe-element {
        position: relative;
        z-index: 2;
        padding: 1.25rem;
        min-height: 60px;
        background: transparent;
    }
    

    
    /* Custom Checkbox */
    .custom-checkbox-wrapper {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .custom-checkbox-wrapper:hover {
        border-color: #6366f1;
        background: linear-gradient(135deg, #faf5ff, #f3e8ff);
    }
    
    .checkbox-container {
        position: relative;
    }
    
    .custom-checkbox-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    .custom-checkbox-label {
        display: flex;
        align-items: flex-start;
        cursor: pointer;
        gap: 1rem;
        margin: 0;
    }
    
    .checkbox-indicator {
        width: 24px;
        height: 24px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .checkbox-indicator i {
        color: white;
        font-size: 0.75rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .custom-checkbox-input:checked + .custom-checkbox-label .checkbox-indicator {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: #6366f1;
    }
    
    .custom-checkbox-input:checked + .custom-checkbox-label .checkbox-indicator i {
        opacity: 1;
    }
    
    .checkbox-title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .checkbox-description {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.4;
    }
    
    /* Payment Button */
    .stripe-payment-button {
        position: relative;
        width: 100%;
        border: none;
        border-radius: 1rem;
        padding: 0;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 60px;
    }
    
    .button-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
        transition: all 0.3s ease;
    }
    
    .stripe-payment-button:hover:not(:disabled) .button-background {
        background: linear-gradient(135deg, #5b61f0 0%, #8250f5 50%, #9f47f6 100%);
    }
    
    .stripe-payment-button:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.4);
    }
    
    .button-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem 2rem;
        color: white;
        font-weight: 600;
        width: 100%;
    }
    
    .payment-state {
        display: flex;
        align-items: center;
        gap: 1rem;
        width: 100%;
        justify-content: center;
    }
    
    .payment-icon {
        font-size: 1.25rem;
    }
    
    .payment-text {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .action-text {
        font-size: 1rem;
        line-height: 1;
    }
    
    .amount-text {
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 0.125rem;
    }
    
    .payment-arrow {
        font-size: 1rem;
        transition: transform 0.3s ease;
    }
    
    .stripe-payment-button:hover:not(:disabled) .payment-arrow {
        transform: translateX(4px);
    }
    
    /* Processing State */
    .processing-state {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .processing-spinner {
        position: relative;
        width: 24px;
        height: 24px;
    }
    
    .spinner-ring {
        width: 24px;
        height: 24px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .processing-text {
        font-size: 1rem;
        font-weight: 600;
    }
    
    .stripe-payment-button:disabled {
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .stripe-payment-button:disabled .button-background {
        background: linear-gradient(135deg, #9ca3af, #6b7280);
    }
    
    /* Security Info */
    .security-info-container {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: 1rem;
    }
    
    .security-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .security-title {
        color: #1f2937;
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
    
    .security-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .security-feature {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .security-feature:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
    }
    
    .feature-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
    }
    
    .feature-icon.ssl { background: linear-gradient(135deg, #10b981, #059669); }
    .feature-icon.pci { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .feature-icon.privacy { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    
    .feature-content {
        flex: 1;
        min-width: 0;
    }
    
    .feature-title {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.875rem;
        line-height: 1.2;
    }
    
    .feature-description {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.125rem;
    }
    
    .security-footer {
        text-align: center;
    }
    
    .security-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        color: #4b5563;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .security-badge i {
        color: #10b981;
    }
    
    /* Error Messages */
    .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        font-weight: 500;
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border: 1px solid #fecaca;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        border-left: 4px solid #dc2626;
        display: none;
    }
    
    .error-message:not(:empty) {
        display: block;
        animation: slideInDown 0.3s ease-out;
    }
    
    /* Animations */
    @keyframes slideInFromRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .payment-section {
        animation: slideInFromRight 0.6s ease-out forwards;
    }
    
    .payment-section:nth-child(2) { animation-delay: 0.1s; }
    .payment-section:nth-child(3) { animation-delay: 0.2s; }
    .payment-section:nth-child(4) { animation-delay: 0.3s; }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .stripe-payment-card {
            border-radius: 1rem;
            margin: 0 0.5rem;
        }
        
        .header-content {
            padding: 1.5rem;
        }
        
        .stripe-logo {
            width: 50px;
            height: 50px;
        }
        
        .stripe-logo i {
            font-size: 1.25rem;
        }
        
        .payment-title h5 {
            font-size: 1.125rem;
        }
        
        .amount-card-content {
            padding: 1.25rem;
            flex-direction: column;
            text-align: center;
        }
        
        .amount-value {
            font-size: 1.5rem;
        }
        
        .security-features {
            grid-template-columns: 1fr;
        }
        
        .security-feature {
            justify-content: center;
            text-align: center;
        }
        

        
        .payment-state {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .payment-arrow {
            display: none;
        }
        
        .change-method-btn span {
            display: none;
        }
        
        .change-method-btn {
            padding: 0.5rem;
            min-width: 40px;
        }
    }
    
    @media (max-width: 480px) {
        .stripe-payment-card {
            margin: 0;
            border-radius: 0.75rem;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
        
        .custom-checkbox-wrapper,
        .security-info-container {
            padding: 1rem;
        }
        
        .amount-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
    
    /* Loading State */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Stripe === 'undefined') {
        console.error('❌ Stripe.js not loaded');
        return;
    }

    const stripeConfig = @json($paymentConfigs['stripe'] ?? []);
    const paymentData = @json($paymentData ?? []);
    
    if (!stripeConfig.publishable_key) {
        console.error('❌ Missing Stripe publishable key');
        document.getElementById('stripe-card-errors').textContent = 'Payment configuration error: Missing publishable key';
        return;
    }
    
    if (!paymentData || !paymentData.client_secret) {
        console.error('❌ Missing Stripe client secret');
        document.getElementById('stripe-card-errors').textContent = 'Payment configuration error: Missing client secret';
        return;
    }

    const stripe = Stripe(stripeConfig.publishable_key);
    const elements = stripe.elements();

    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '18px',
                color: '#495057',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                fontWeight: '500',
                '::placeholder': {
                    color: '#adb5bd',
                    fontWeight: '400',
                },
                iconColor: '#667eea',
            },
            invalid: {
                color: '#dc3545',
                iconColor: '#dc3545',
            },
            complete: {
                color: '#198754',
                iconColor: '#198754',
            },
        },
        hidePostalCode: true,
    });

    const cardContainer = document.getElementById('stripe-card-element');
    if (!cardContainer) {
        console.error('❌ Card element container not found');
        return;
    }
    
    try {
        cardElement.mount('#stripe-card-element');
        
        // Add focus and blur event handlers for better UX
        const cardWrapper = document.querySelector('.card-input-wrapper');
        
        cardElement.on('focus', function() {
            cardWrapper.classList.add('focused');
        });
        
        cardElement.on('blur', function() {
            cardWrapper.classList.remove('focused');
        });
        
    } catch (error) {
        console.error('❌ Failed to mount card element:', error);
        document.getElementById('stripe-card-errors').textContent = 'Failed to initialize payment form';
    }

    // Handle real-time validation errors from the card Element
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('stripe-card-errors');
        const cardWrapper = document.querySelector('.card-input-wrapper');
        
        if (event.error) {
            displayError.textContent = event.error.message;
            cardWrapper.classList.add('invalid');
            cardWrapper.classList.remove('complete');
        } else {
            displayError.textContent = '';
            cardWrapper.classList.remove('invalid');
            
            if (event.complete) {
                cardWrapper.classList.add('complete');
            } else {
                cardWrapper.classList.remove('complete');
            }
        }
    });

    // Handle form submission
    const form = document.getElementById('stripe-payment-form');
    const submitButton = document.getElementById('stripe-submit-payment');

    if (!form) {
        console.error('❌ Payment form not found');
        return;
    }
    
    if (!submitButton) {
        console.error('❌ Submit button not found');
        return;
    }
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        document.body.classList.add('loading');

        try {
            const {error, paymentIntent} = await stripe.confirmCardPayment(paymentData.client_secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: @json($order->user_name),
                        email: @json($order->user_email),
                    },
                }
            });

            if (error) {
                console.error('❌ Payment failed:', error);
                // Show error to customer
                document.getElementById('stripe-card-errors').textContent = error.message;
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Pay $' + @json(number_format($order->total, 2));
                document.body.classList.remove('loading');
                
                // Call Livewire method directly
                @this.call('handlePaymentFailure', error.message, error.type || '', error.code || '', 'stripe');
            } else {
                // Payment succeeded - show success state and call Livewire
                submitButton.innerHTML = '<i class="fas fa-check"></i> Payment Successful!';
                submitButton.classList.remove('btn-primary');
                submitButton.classList.add('btn-success');
                
                // Call Livewire method directly
                @this.call('handlePaymentSuccess', paymentIntent.id, 'stripe');
                
                // Don't remove loading class here - let Livewire handle the redirect
                return; // Prevent further execution
            }
        } catch (err) {
            console.error('❌ Unexpected error during payment:', err);
            document.getElementById('stripe-card-errors').textContent = 'An unexpected error occurred. Please try again.';
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Pay $' + @json(number_format($order->total, 2));
            document.body.classList.remove('loading');
        }
    });
});
</script>
@endpush
@endif
