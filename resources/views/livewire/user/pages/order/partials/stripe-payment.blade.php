<!-- Stripe Payment Container -->
<div class="bg-slate-50 dark:bg-slate-900 flex flex-col">
    <!-- Payment Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 p-6 md:p-8 text-white mt-4 mx-4 rounded-3xl shadow-xl mx-4 mt-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <i class="fab fa-stripe text-lg md:text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg md:text-xl font-bold mb-1">Sichere Zahlung</h4>
                    <p class="text-indigo-100 dark:text-indigo-200 text-sm md:text-base">Geben Sie Ihre Kartendetails unten ein</p>
                </div>
            </div>
            <!-- Total Amount in Header -->
            <div class="text-right">
                <div class="flex items-center gap-2 text-white/90 text-xs md:text-sm font-medium mb-1">
                    <i class="fas fa-shield-alt"></i>
                    <span>Sichere Zahlung</span>
                </div>
                <div class="text-2xl md:text-3xl font-bold">€{{ number_format($order->total, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-8 flex flex-col">
        @if($paymentData && $paymentData['success'])
            <form id="stripe-payment-form" wire:key="stripe-form-{{ $currentTransaction->id ?? 'new' }}" wire:ignore.self
                  x-data="{ mounted: false }" x-on:submit.prevent
                  x-init="
                      $nextTick(() => {
                          document.dispatchEvent(new CustomEvent('step-3-entered'));
                          mounted = true;
                      })
                  "
                  class="flex flex-col">
                <!-- Card Information Section -->
                <div class="mb-4 md:mb-6">
                    <div class="flex items-center gap-3 mb-3 md:mb-4">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-indigo-600 dark:text-indigo-400 text-xs md:text-sm"></i>
                        </div>
                        <label class="text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Karteninformationen
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 p-3 md:p-4 transition-all duration-300 focus-within:border-indigo-400 dark:focus-within:border-indigo-500 focus-within:bg-white dark:focus-within:bg-slate-700 focus-within:shadow-lg">
                        <div id="stripe-card-element" class="stripe-element" wire:ignore>
                            <!-- Stripe Elements will create form elements here -->
                        </div>
                    </div>
                    <div id="stripe-card-errors" role="alert" class="text-red-600 dark:text-red-400 text-xs md:text-sm mt-2 font-medium"></div>
                </div>

                <!-- Payment Buttons -->
                <div class="flex flex-col md:flex-row gap-3 md:gap-4">
                    <!-- Back Button -->
                    <button type="button" wire:click="goBack"
                            class="cursor-pointer group inline-flex items-center justify-center gap-2 md:gap-3 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-200 border-2 border-slate-300 dark:border-slate-600 px-6 md:px-8 py-3 md:py-4 rounded-full font-semibold text-base md:text-lg shadow-md hover:shadow-lg hover:-translate-y-1 hover:border-slate-400 dark:hover:border-slate-500 transition-all duration-300 order-2 md:order-1 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $isProcessing ? 'disabled' : '' }}>
                        <div class="w-5 h-5 md:w-6 md:h-6 bg-slate-100 dark:bg-slate-600 rounded-full flex items-center justify-center group-hover:bg-slate-200 dark:group-hover:bg-slate-500 group-hover:-translate-x-1 transition-all duration-300">
                            <i class="fas fa-arrow-left text-xs md:text-sm"></i>
                        </div>
                        Zurück
                    </button>

                    <!-- Payment Button -->
                    <button type="submit" id="stripe-submit-payment"
                            class="cursor-pointer group relative flex-1 inline-flex items-center justify-center gap-3 md:gap-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold text-base md:text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden order-1 md:order-2 disabled:opacity-75 disabled:cursor-not-allowed"
                            {{ $isProcessing ? 'disabled' : '' }}>

                        <span id="stripe-button-text" class="relative z-10 flex items-center gap-3 md:gap-4">
                            @if($isProcessing)
                                <div class="flex items-center gap-2 md:gap-3">
                                    <div class="w-4 h-4 md:w-5 md:h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    <span class="text-sm md:text-base">Processing Payment...</span>
                                </div>
                            @else
                                <div class="flex items-center gap-3 md:gap-4">
                                    <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-xs md:text-sm"></i>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="text-sm md:text-base">Pay Securely</span>
                                        <span class="text-lg md:text-xl font-bold">€{{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 group-hover:translate-x-1 transition-all duration-300">
                                        <i class="fas fa-arrow-right text-xs md:text-sm"></i>
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
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 md:p-6 flex-1 flex items-center justify-center">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1 text-base md:text-lg">Payment Initialization Failed</h4>
                        <p class="text-gray-700 text-sm md:text-base">Unable to initialize Stripe payment. Please try again or contact support.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if($paymentData && $paymentData['success'])
@push('styles')
<style>
    /* Essential Stripe Elements Styling */
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
    // Note: do not rely on a global flag for handler attachment as Livewire re-renders DOM nodes

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
        if (!form) {
            return;
        }

        // Prevent multiple handlers after Livewire/Alpine re-renders by marking the current form
        if (form.dataset.stripeHandlerAttached === '1') {
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

        form.dataset.stripeHandlerAttached = '1';
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
