<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fab fa-paypal me-2"></i> PayPal Payment
        </h5>
        @if(count($availableProviders) > 1)
            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="$set('selectedProvider', '')">
                <i class="fas fa-arrow-left"></i> Change Method
            </button>
        @endif
    </div>
    <div class="card-body">
        @if($paymentData && $paymentData['success'])
            <div class="text-center mb-4">
                <p class="text-muted">You will be redirected to PayPal to complete your payment securely.</p>
            </div>

            <div id="paypal-button-container" class="mb-4"></div>

            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-shield-alt"></i> 
                    Secure payment powered by PayPal
                </small>
            </div>
        @else
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                Unable to initialize PayPal payment. Please try again or contact support.
            </div>
        @endif
    </div>
</div>

@if($paymentData && $paymentData['success'])
@push('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ $paymentConfigs['paypal']['client_id'] ?? '' }}&currency={{ $paymentConfigs['paypal']['currency'] ?? 'USD' }}&intent=capture"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentData = @json($paymentData);
    const paypalConfig = @json($paymentConfigs['paypal'] ?? []);
    
    if (typeof paypal === 'undefined') {
        console.error('PayPal SDK not loaded');
        return;
    }

    if (!paymentData.order_id) {
        console.error('PayPal order ID not found');
        return;
    }

    paypal.Buttons({
        // Set up the transaction
        createOrder: function(data, actions) {
            // Return the order ID from our backend
            return paymentData.order_id;
        },

        // Finalize the transaction
        onApprove: function(data, actions) {
            // Show loading state
            document.body.classList.add('loading');
            
            // Capture the order
            return actions.order.capture().then(function(details) {
                // Emit success event to Livewire
                @this.dispatch('payment-succeeded', data.orderID, 'paypal');
            }).catch(function(error) {
                document.body.classList.remove('loading');
                console.error('PayPal capture error:', error);
                @this.dispatch('payment-failed', {
                    message: 'Payment capture failed',
                    error: error
                }, 'paypal');
            });
        },

        // Handle errors
        onError: function(err) {
            document.body.classList.remove('loading');
            console.error('PayPal error:', err);
            @this.dispatch('payment-failed', {
                message: 'PayPal payment failed',
                error: err
            }, 'paypal');
        },

        // Handle cancellation
        onCancel: function(data) {
            document.body.classList.remove('loading');
            console.log('PayPal payment cancelled');
            @this.dispatch('payment-failed', {
                message: 'Payment was cancelled',
                cancelled: true
            }, 'paypal');
        },

        style: {
            layout: 'vertical',
            color: 'blue',
            shape: 'rect',
            label: 'paypal'
        }
    }).render('#paypal-button-container');
});
</script>
@endpush
@endif
