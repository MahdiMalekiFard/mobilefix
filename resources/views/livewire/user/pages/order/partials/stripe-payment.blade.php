<!-- Stripe Payment Container -->
<div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
    <!-- Payment Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                <i class="fab fa-stripe text-xl"></i>
            </div>
            <div>
                <h4 class="text-xl font-bold mb-1">Secure Payment</h4>
                <p class="text-indigo-100">Enter your card details below</p>
            </div>
        </div>
    </div>

    <div class="p-8">
        @if($paymentData && $paymentData['success'])
            <!-- Payment Amount Display -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-6 mb-8 border border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Amount</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-2 text-green-600 text-sm font-medium">
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure Payment</span>
                        </div>
                    </div>
                </div>
            </div>

            <form id="stripe-payment-form" wire:key="stripe-form-{{ $currentTransaction->id ?? 'new' }}" wire:ignore.self 
                  x-data="{ mounted: false }" 
                  x-init="
                      $nextTick(() => {
                          document.dispatchEvent(new CustomEvent('step-3-entered'));
                          mounted = true;
                      })
                  ">
                <!-- Card Information Section -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-indigo-600 text-sm"></i>
                        </div>
                        <label class="text-lg font-semibold text-gray-900">
                            Card Information
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                    </div>
                    
                    <div class="bg-slate-50 rounded-2xl border-2 border-slate-200 p-4 transition-all duration-300 focus-within:border-indigo-400 focus-within:bg-white focus-within:shadow-lg">
                        <div id="stripe-card-element" class="stripe-element" wire:ignore>
                            <!-- Stripe Elements will create form elements here -->
                        </div>
                    </div>
                    <div id="stripe-card-errors" role="alert" class="text-red-600 text-sm mt-2 font-medium"></div>
                </div>

                <!-- Save Card Option -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 mb-8 border border-blue-200">
                    <label class="flex items-start gap-4 cursor-pointer">
                        <div class="relative mt-1">
                            <input type="checkbox" id="save-card" name="save-card" 
                                   class="w-5 h-5 rounded border-2 border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-2 focus:ring-offset-2">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-bookmark text-indigo-600"></i>
                                <span class="font-semibold text-gray-900">Save this card for future payments</span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Securely store your payment method for faster checkout next time
                            </p>
                        </div>
                    </label>
                </div>

                <!-- Payment Buttons -->
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Back Button -->
                    <button type="button" wire:click="goBack" 
                            class="group inline-flex items-center justify-center gap-3 bg-white text-gray-700 border-2 border-slate-300 px-8 py-4 rounded-full font-semibold text-lg shadow-md hover:shadow-lg hover:-translate-y-1 hover:border-slate-400 transition-all duration-300 order-2 md:order-1"
                            {{ $isProcessing ? 'disabled' : '' }}>
                        <div class="w-6 h-6 bg-slate-100 rounded-full flex items-center justify-center group-hover:bg-slate-200 group-hover:-translate-x-1 transition-all duration-300">
                            <i class="fas fa-arrow-left text-sm"></i>
                        </div>
                        Go Back
                    </button>
                    
                    <!-- Payment Button -->
                    <button type="submit" id="stripe-submit-payment" 
                            class="group relative flex-1 inline-flex items-center justify-center gap-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden order-1 md:order-2"
                            {{ $isProcessing ? 'disabled opacity-75' : '' }}>
                        
                        <span id="stripe-button-text" class="relative z-10 flex items-center gap-4">
                            @if($isProcessing)
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    <span>Processing Payment...</span>
                                </div>
                            @else
                                <div class="flex items-center gap-4">
                                    <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-sm"></i>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="text-base">Pay Securely</span>
                                        <span class="text-xl font-bold">${{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 group-hover:translate-x-1 transition-all duration-300">
                                        <i class="fas fa-arrow-right text-sm"></i>
                                    </div>
                                </div>
                            @endif
                        </span>
                        
                        <!-- Hover Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-green-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </div>
            </form>
        @else
            <!-- Error State -->
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Payment Initialization Failed</h4>
                        <p class="text-gray-700">Unable to initialize Stripe payment. Please try again or contact support.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if($paymentData && $paymentData['success'])
@push('styles')
<style>
    /* ===== STRIPE ELEMENTS INTEGRATION ===== */
    
    /* Stripe Elements Styling */
    .stripe-element {
        padding: 16px;
        font-size: 16px;
        min-height: 24px;
    }
    
    .stripe-element.focused {
        outline: none;
    }
    
    .stripe-element.invalid {
        border-color: #ef4444;
    }
    
    .stripe-element.complete {
        border-color: #10b981;
    }
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let stripeInstance = null;
    let cardElement = null;
    let formHandlerAttached = false;
    
    // Make the function globally available
    window.initStripePayment = function initStripe() {
        if (typeof Stripe === 'undefined') {
            console.error('❌ Stripe.js not loaded');
            return;
        }
        const stripeConfig = @json($paymentConfigs['stripe'] ?? []);
        const paymentData = @json($paymentData ?? []);
        
        if (!stripeConfig.publishable_key) {
            console.error('❌ Missing Stripe publishable key');
            return;
        }
        
        if (!paymentData || !paymentData.client_secret) {
            console.error('❌ Missing Stripe client secret');
            return;
        }

        const cardContainer = document.getElementById('stripe-card-element');
        if (!cardContainer) {
            console.log('❌ Card element container not found');
            return;
        }

        // Clear the container completely
        cardContainer.innerHTML = '';

        // Clean up previous instance
        if (cardElement) {
            try {
                cardElement.unmount();
            } catch (e) {
                console.log('Previous card element cleaned up');
            }
            cardElement = null;
        }

        // Initialize Stripe instance if not exists
        if (!stripeInstance) {
            stripeInstance = Stripe(stripeConfig.publishable_key);
            console.log('✅ Stripe instance created');
        }

        const elements = stripeInstance.elements();
        cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#374151',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                    fontWeight: '500',
                    '::placeholder': {
                        color: '#9CA3AF',
                        fontWeight: '400',
                    },
                    iconColor: '#6366f1',
                },
                invalid: {
                    color: '#ef4444',
                    iconColor: '#ef4444',
                },
                complete: {
                    color: '#10b981',
                    iconColor: '#10b981',
                },
            },
            hidePostalCode: true,
        });

        try {
            cardElement.mount('#stripe-card-element');
            console.log('✅ Stripe card element mounted successfully');
            
        } catch (error) {
            console.error('❌ Failed to mount card element:', error);
            const errorElement = document.getElementById('stripe-card-errors');
            if (errorElement) {
                errorElement.textContent = 'Failed to initialize payment form';
            }
        }

        // Handle card change events
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('stripe-card-errors');
            if (displayError) {
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            }
        });
    }

    function setupFormHandler() {
        const form = document.getElementById('stripe-payment-form');
        if (!form || formHandlerAttached) {
            return;
        }

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const submitButton = document.getElementById('stripe-submit-payment');
            if (!submitButton || !cardElement) {
                console.error('❌ Submit button or card element not found');
                return;
            }
            
            submitButton.disabled = true;
            document.body.classList.add('loading');

            try {
                const {error, paymentIntent} = await stripeInstance.confirmCardPayment(@json($paymentData['client_secret']), {
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
                    const errorElement = document.getElementById('stripe-card-errors');
                    if (errorElement) {
                        errorElement.textContent = error.message;
                    }
                    submitButton.disabled = false;
                    document.body.classList.remove('loading');
                    
                    @this.call('handlePaymentFailure', error.message, error.type || '', error.code || '', 'stripe');
                } else {
                    @this.call('handlePaymentSuccess', paymentIntent.id, 'stripe');
                }
            } catch (err) {
                console.error('❌ Unexpected error during payment:', err);
                const errorElement = document.getElementById('stripe-card-errors');
                if (errorElement) {
                    errorElement.textContent = 'An unexpected error occurred. Please try again.';
                }
                submitButton.disabled = false;
                document.body.classList.remove('loading');
            }
        });

        formHandlerAttached = true;
        console.log('✅ Form handler attached');
    }

    // Listen for step-3-entered event from Livewire
    document.addEventListener('step-3-entered', function() {
        console.log('🎯 Step 3 entered - forcing Stripe reinitialization');
        setTimeout(() => {
            window.initStripePayment();
            setupFormHandler();
        }, 100);
    });

    // Initial setup for when page loads directly on step 3
    setTimeout(() => {
        window.initStripePayment();
        setupFormHandler();
    }, 100);
});
</script>
@endpush
@endif
    
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
    let stripeInstance = null;
    let cardElement = null;
    let formHandlerAttached = false;
    
    function forceStripeReinit() {
        console.log('🔄 Force reinitializing Stripe...');
        
        // Clear error messages
        const errorElement = document.getElementById('stripe-card-errors');
        if (errorElement) {
            errorElement.textContent = '';
        }
        
        // Force unmount any existing card element
        if (cardElement) {
            try {
                cardElement.unmount();
                console.log('✅ Previous card element unmounted');
            } catch (e) {
                console.log('Previous card element was already unmounted');
            }
            cardElement = null;
        }
        
        // Clear wrapper classes
        const cardWrapper = document.querySelector('.card-input-wrapper');
        if (cardWrapper) {
            cardWrapper.classList.remove('focused', 'invalid', 'complete');
        }
        
        // Force reinitialize
        initStripePayment();
    }
    
    function initStripePayment() {
        if (typeof Stripe === 'undefined') {
            console.error('❌ Stripe.js not loaded');
            return;
        }

        const stripeConfig = @json($paymentConfigs['stripe'] ?? []);
        const paymentData = @json($paymentData ?? []);
        
        if (!stripeConfig.publishable_key) {
            console.error('❌ Missing Stripe publishable key');
            return;
        }
        
        if (!paymentData || !paymentData.client_secret) {
            console.error('❌ Missing Stripe client secret');
            return;
        }

        const cardContainer = document.getElementById('stripe-card-element');
        if (!cardContainer) {
            console.log('❌ Card element container not found');
            return;
        }

        // Clear the container completely
        cardContainer.innerHTML = '';

        // Initialize Stripe instance if not exists
        if (!stripeInstance) {
            stripeInstance = Stripe(stripeConfig.publishable_key);
            console.log('✅ Stripe instance created');
        }

        const elements = stripeInstance.elements();
        cardElement = elements.create('card', {
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

        try {
            cardElement.mount('#stripe-card-element');
            console.log('✅ Stripe card element mounted successfully');
            
            // Add event handlers
            const cardWrapper = document.querySelector('.card-input-wrapper');
            
            cardElement.on('focus', function() {
                if (cardWrapper) cardWrapper.classList.add('focused');
            });
            
            cardElement.on('blur', function() {
                if (cardWrapper) cardWrapper.classList.remove('focused');
            });

            cardElement.on('change', function(event) {
                const displayError = document.getElementById('stripe-card-errors');
                const cardWrapper = document.querySelector('.card-input-wrapper');
                
                if (displayError) {
                    if (event.error) {
                        displayError.textContent = event.error.message;
                        if (cardWrapper) {
                            cardWrapper.classList.add('invalid');
                            cardWrapper.classList.remove('complete');
                        }
                    } else {
                        displayError.textContent = '';
                        if (cardWrapper) {
                            cardWrapper.classList.remove('invalid');
                            if (event.complete) {
                                cardWrapper.classList.add('complete');
                            } else {
                                cardWrapper.classList.remove('complete');
                            }
                        }
                    }
                }
            });

        } catch (error) {
            console.error('❌ Failed to mount card element:', error);
            const errorElement = document.getElementById('stripe-card-errors');
            if (errorElement) {
                errorElement.textContent = 'Failed to initialize payment form';
            }
        }
    }

    function setupFormHandler() {
        const form = document.getElementById('stripe-payment-form');
        if (!form || formHandlerAttached) {
            return;
        }

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const submitButton = document.getElementById('stripe-submit-payment');
            if (!submitButton || !cardElement) {
                console.error('❌ Submit button or card element not found');
                return;
            }
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            document.body.classList.add('loading');

            try {
                const {error, paymentIntent} = await stripeInstance.confirmCardPayment(@json($paymentData['client_secret']), {
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
                    const errorElement = document.getElementById('stripe-card-errors');
                    if (errorElement) {
                        errorElement.textContent = error.message;
                    }
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Pay $' + @json(number_format($order->total, 2));
                    document.body.classList.remove('loading');
                    
                    @this.call('handlePaymentFailure', error.message, error.type || '', error.code || '', 'stripe');
                } else {
                    submitButton.innerHTML = '<i class="fas fa-check"></i> Payment Successful!';
                    submitButton.classList.remove('btn-primary');
                    submitButton.classList.add('btn-success');
                    
                    @this.call('handlePaymentSuccess', paymentIntent.id, 'stripe');
                }
            } catch (err) {
                console.error('❌ Unexpected error during payment:', err);
                const errorElement = document.getElementById('stripe-card-errors');
                if (errorElement) {
                    errorElement.textContent = 'An unexpected error occurred. Please try again.';
                }
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Pay $' + @json(number_format($order->total, 2));
                document.body.classList.remove('loading');
            }
        });

        formHandlerAttached = true;
        console.log('✅ Form handler attached');
    }

    // Listen for step-3-entered event from Livewire
    document.addEventListener('step-3-entered', function() {
        console.log('🎯 Step 3 entered - forcing Stripe reinitialization');
        setTimeout(() => {
            forceStripeReinit();
            setupFormHandler();
        }, 100);
    });

    // Initial setup for when page loads directly on step 3
    setTimeout(() => {
        initStripePayment();
        setupFormHandler();
    }, 100);
});
</script>
@endpush
@endif
