<div class="p-8">
    @if($selectedProvider === 'stripe')
        <!-- Load Stripe Payment Partial -->
        @include('livewire.user.pages.order.partials.stripe-payment')
        
    @elseif($selectedProvider === 'paypal')
        <!-- PayPal Payment -->
        <div class="text-center">
            <p class="text-gray-600 mb-4">You will be redirected to PayPal to complete your payment securely.</p>
            
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <!-- Back Button -->
                <button type="button" wire:click="goBack" 
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 order-2 md:order-1 disabled:opacity-50 disabled:cursor-not-allowed"
                        @if($isProcessing) disabled @endif>
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </button>
                
                <!-- PayPal Payment Button -->
                <button wire:click="processPayment" 
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#0070ba] text-white rounded-lg font-semibold hover:bg-[#005ea6] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0070ba] transition-colors duration-200 order-1 md:order-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        @if($isProcessing) disabled @endif>
                    @if($isProcessing)
                        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        Processing...
                    @else
                        <i class="fab fa-paypal"></i>
                        Pay with PayPal - ${{ number_format($order->total, 2) }}
                    @endif
                </button>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-800">
                        <strong>Payment provider not configured:</strong> {{ $selectedProvider }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
