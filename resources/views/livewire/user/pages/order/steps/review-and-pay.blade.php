<div class="payment-container">
    @if($selectedProvider === 'stripe')
        <!-- Load Stripe Payment Partial -->
        @include('livewire.user.pages.order.partials.stripe-payment')
        
    @elseif($selectedProvider === 'paypal')
        <!-- PayPal Payment -->
        <div class="text-center">
            <p class="text-muted mb-4">You will be redirected to PayPal to complete your payment securely.</p>
            
            <div class="d-flex gap-3 flex-column flex-md-row justify-content-center">
                <!-- Back Button -->
                <button type="button" wire:click="goBack" 
                        class="btn btn-outline-secondary order-2 order-md-1"
                        @if($isProcessing) disabled @endif>
                    <i class="fas fa-arrow-left me-2"></i>
                    Go Back
                </button>
                
                <!-- PayPal Payment Button -->
                <button wire:click="processPayment" 
                        class="btn btn-lg flex-fill order-1 order-md-2"
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
