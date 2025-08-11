<div class="payment-container">
    @if($selectedProvider === 'stripe')
        <!-- Load Stripe Payment Partial -->
        @include('livewire.user.pages.order.partials.stripe-payment')
        
    @elseif($selectedProvider === 'paypal')
        <!-- PayPal Payment -->
        <div class="text-center">
            <p class="text-muted mb-4">You will be redirected to PayPal to complete your payment securely.</p>
            <button wire:click="processPayment" 
                    class="btn btn-lg w-100"
                    style="background: #0070ba; color: white; border: none; padding: 1rem; border-radius: 8px; font-weight: 600;"
                    @if($isProcessing) disabled @endif>
                @if($isProcessing)
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Processing...
                @else
                    <i class="fab fa-paypal me-2"></i>
                    Pay with PayPal - ${{ number_format($order->total, 2) }}
                @endif
            </button>
        </div>
    @else
        <div class="alert alert-warning">
            <strong>Payment provider not configured:</strong> {{ $selectedProvider }}
        </div>
    @endif
</div>

@push('styles')
<style>
.payment-container {
    padding: 2rem;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-header {
    border-bottom: none;
    padding: 1.5rem;
}

/* Responsive Design */
@media (max-width: 576px) {
    .payment-container {
        padding: 1rem;
    }
    
    .card-header {
        padding: 1rem;
    }
}
</style>
@endpush
