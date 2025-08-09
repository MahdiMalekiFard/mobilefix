<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fab fa-stripe me-2"></i> Stripe Payment
        </h5>
        @if(count($availableProviders) > 1)
            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="$set('selectedProvider', '')">
                <i class="fas fa-arrow-left"></i> Change Method
            </button>
        @endif
    </div>
    <div class="card-body">
        @if($paymentData && $paymentData['success'])
            <form id="stripe-payment-form">
                <div class="mb-4">
                    <label class="form-label">Card Information</label>
                    <div id="stripe-card-element" class="form-control" style="height: 40px; padding: 10px;">
                        <!-- Stripe Elements will create form elements here -->
                    </div>
                    <div id="stripe-card-errors" role="alert" class="text-danger mt-2"></div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="save-card" name="save-card">
                        <label class="form-check-label" for="save-card">
                            Save this card for future payments
                        </label>
                    </div>
                </div>

                <button type="submit" id="stripe-submit-payment" class="btn btn-primary btn-lg w-100" {{ $isProcessing ? 'disabled' : '' }}>
                    <span id="stripe-button-text">
                        @if($isProcessing)
                            <i class="fas fa-spinner fa-spin"></i> Processing...
                        @else
                            <i class="fas fa-credit-card"></i> Pay ${{ number_format($order->total, 2) }}
                        @endif
                    </span>
                </button>
            </form>

            <div class="mt-4 text-center">
                <small class="text-muted">
                    <i class="fas fa-lock"></i> 
                    Your payment information is secure and encrypted.
                </small>
            </div>
        @else
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                Unable to initialize Stripe payment. Please try again or contact support.
            </div>
        @endif
    </div>
</div>

@if($paymentData && $paymentData['success'])
@push('styles')
<style>
    #stripe-card-element {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.75rem;
        background: white;
    }
    
    #stripe-card-element.focused {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    #stripe-card-element.invalid {
        border-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔵 Stripe payment initialization started');
    
    if (typeof Stripe === 'undefined') {
        console.error('❌ Stripe.js not loaded');
        return;
    }
    console.log('✅ Stripe.js loaded successfully');

    const stripeConfig = @json($paymentConfigs['stripe'] ?? []);
    const paymentData = @json($paymentData);
    
    console.log('🔍 Stripe Config:', stripeConfig);
    console.log('🔍 Payment Data:', paymentData);
    
    if (!stripeConfig.publishable_key) {
        console.error('❌ Missing Stripe publishable key');
        document.getElementById('stripe-card-errors').textContent = 'Payment configuration error: Missing publishable key';
        return;
    }
    
    if (!paymentData.client_secret) {
        console.error('❌ Missing Stripe client secret');
        document.getElementById('stripe-card-errors').textContent = 'Payment configuration error: Missing client secret';
        return;
    }

    console.log('🔵 Initializing Stripe with publishable key:', stripeConfig.publishable_key.substring(0, 12) + '...');
    
    const stripe = Stripe(stripeConfig.publishable_key);
    const elements = stripe.elements();

    // Create card element
    console.log('🔵 Creating card element...');
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });

    console.log('🔵 Mounting card element to #stripe-card-element...');
    const cardContainer = document.getElementById('stripe-card-element');
    if (!cardContainer) {
        console.error('❌ Card element container not found');
        return;
    }
    
    try {
        cardElement.mount('#stripe-card-element');
        console.log('✅ Card element mounted successfully');
    } catch (error) {
        console.error('❌ Failed to mount card element:', error);
        document.getElementById('stripe-card-errors').textContent = 'Failed to initialize payment form';
    }

    // Handle real-time validation errors from the card Element
    cardElement.on('change', function(event) {
        console.log('🔵 Card element change event:', event);
        const displayError = document.getElementById('stripe-card-errors');
        if (event.error) {
            console.log('❌ Card validation error:', event.error);
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
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
    
    console.log('✅ Form and submit button found, attaching event listener');

    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        console.log('🔵 Payment form submitted');
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        document.body.classList.add('loading');

        console.log('🔵 Confirming card payment with client secret:', paymentData.client_secret.substring(0, 20) + '...');

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

            console.log('🔍 Stripe response:', { error, paymentIntent });

            if (error) {
                console.error('❌ Payment failed:', error);
                // Show error to customer
                document.getElementById('stripe-card-errors').textContent = error.message;
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-credit-card"></i> Pay $' + @json(number_format($order->total, 2));
                document.body.classList.remove('loading');
                
                // Emit failure event to Livewire
                @this.dispatch('payment-failed', {
                    message: error.message,
                    type: error.type,
                    code: error.code
                }, 'stripe');
            } else {
                console.log('✅ Payment succeeded:', paymentIntent);
                // Payment succeeded - show success state and dispatch event
                submitButton.innerHTML = '<i class="fas fa-check"></i> Payment Successful!';
                submitButton.classList.remove('btn-primary');
                submitButton.classList.add('btn-success');
                
                // Dispatch success event to Livewire
                @this.dispatch('payment-succeeded', paymentIntent.id, 'stripe');
                
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
