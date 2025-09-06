<!-- Review and Pay Container -->
<div class="bg-slate-50 flex flex-col">
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 rounded-3xl p-6 md:p-8 mb-6 text-white shadow-xl mx-4 mt-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
            <div class="flex items-center gap-4 md:gap-6 text-center md:text-left">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold mb-2">Review & Pay</h3>
                    <p class="text-indigo-100 text-base md:text-lg">Review your order details and complete payment</p>
                </div>
            </div>
            
            <!-- Navigation Button in Header -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="goToStep(2)" 
                        class="group inline-flex items-center gap-3 bg-white/20 backdrop-blur-sm text-white border-2 border-white/30 px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold text-sm md:text-base shadow-md hover:bg-white/30 hover:border-white/50 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="w-4 h-4 md:w-5 md:h-5 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 group-hover:-translate-x-1 transition-all duration-300">
                        <i class="fas fa-arrow-left text-xs md:text-sm"></i>
                    </div>
                    Back to Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 flex flex-col gap-6 mb-6">
        <!-- Order Summary Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
            <!-- Order Header -->
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 md:px-8 py-4 md:py-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 md:gap-4">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-indigo-600 text-lg md:text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg md:text-xl font-bold text-gray-900">Order Summary</h4>
                            <p class="text-gray-600 text-sm md:text-base">Order #{{ $order->order_number }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900">€{{ number_format($order->total, 2) }}</div>
                        <div class="text-sm text-gray-600">Total Amount</div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="p-6 md:p-8 space-y-6">
                <!-- Device Information -->
                @if($order->device && $order->brand)
                <div class="flex items-start gap-4 md:gap-6 p-4 md:p-6 bg-slate-50 rounded-2xl">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-mobile-alt text-blue-600 text-lg md:text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h5 class="text-lg md:text-xl font-semibold text-gray-900 mb-1">{{ $order->brand->name }} {{ $order->device->name }}</h5>
                        <p class="text-gray-600 text-sm md:text-base mb-3">Device Model</p>
                        @if($order->problems && $order->problems->count() > 0)
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-700">Issues to fix:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($order->problems as $problem)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                            <i class="fas fa-exclamation-circle text-xs"></i>
                                            {{ $problem->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Delivery Address -->
                @if($selectedAddress)
                <div class="flex items-start gap-4 md:gap-6 p-4 md:p-6 bg-slate-50 rounded-2xl">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-map-marker-alt text-green-600 text-lg md:text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h5 class="text-lg md:text-xl font-semibold text-gray-900 mb-1">{{ $selectedAddress->title }}</h5>
                        <p class="text-gray-600 text-sm md:text-base mb-2">Delivery Address</p>
                        <p class="text-gray-700 text-sm md:text-base leading-relaxed">{{ $selectedAddress->address }}</p>
                    </div>
                </div>
                @endif

                <!-- Payment Method -->
                <div class="flex items-start gap-4 md:gap-6 p-4 md:p-6 bg-slate-50 rounded-2xl">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        @if($selectedProvider === 'stripe')
                            <i class="fab fa-stripe text-purple-600 text-lg md:text-xl"></i>
                        @elseif($selectedProvider === 'paypal')
                            <i class="fab fa-paypal text-purple-600 text-lg md:text-xl"></i>
                        @else
                            <i class="fas fa-credit-card text-purple-600 text-lg md:text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h5 class="text-lg md:text-xl font-semibold text-gray-900 mb-1">
                            @if($selectedProvider === 'stripe')
                                Stripe Payment
                            @elseif($selectedProvider === 'paypal')
                                PayPal Payment
                            @else
                                {{ ucfirst($selectedProvider) }}
                            @endif
                        </h5>
                        <p class="text-gray-600 text-sm md:text-base mb-2">Payment Method</p>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-alt text-green-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-700">Secure & Encrypted</span>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="border-t border-slate-200 pt-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 text-base md:text-lg">Repair Service</span>
                            <span class="text-gray-900 font-semibold text-base md:text-lg">€{{ number_format($order->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 text-base md:text-lg">Tax (included)</span>
                            <span class="text-gray-600 text-base md:text-lg">€0.00</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xl md:text-2xl font-bold text-gray-900">Total</span>
                                <span class="text-xl md:text-2xl font-bold text-gray-900">€{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Action Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden" x-data="{ showPaymentForm: false }">
            <div class="p-6 md:p-8">
    @if($selectedProvider === 'stripe')
                    <!-- Stripe Payment Button -->
                    <div class="text-center">
                        <div class="mb-6">
                            <h4 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">Ready to Pay?</h4>
                            <p class="text-gray-600 text-base md:text-lg">Click below to securely complete your payment with Stripe</p>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-4 justify-center">
                            <!-- Back Button -->
                            <button type="button" wire:click="goBack" 
                                    class="inline-flex items-center justify-center gap-3 bg-white text-gray-700 border-2 border-slate-300 px-6 md:px-8 py-3 md:py-4 rounded-full font-semibold text-base md:text-lg shadow-md hover:shadow-lg hover:-translate-y-1 hover:border-slate-400 transition-all duration-300 order-2 md:order-1 disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ $isProcessing ? 'disabled' : '' }}>
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-slate-100 rounded-full flex items-center justify-center hover:bg-slate-200 hover:-translate-x-1 transition-all duration-300">
                                    <i class="fas fa-arrow-left text-xs md:text-sm"></i>
                                </div>
                                Back
                            </button>

                            <!-- Stripe Checkout Button -->
                            <button type="button" 
                                    wire:click="redirectToStripeCheckout"
                                    class="group relative flex-1 max-w-md inline-flex items-center justify-center gap-3 md:gap-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 md:px-8 py-4 md:py-5 rounded-full font-bold text-base md:text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden order-1 md:order-2 disabled:opacity-75 disabled:cursor-not-allowed"
                                    {{ $isProcessing ? 'disabled' : '' }}>
                                
                                <div class="relative z-10 flex items-center gap-3 md:gap-4">
                                    @if($isProcessing)
                                        <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                        <span>Opening Stripe...</span>
                                    @else
                                        <div class="w-6 h-6 md:w-8 md:h-8 bg-white/20 rounded-full flex items-center justify-center">
                                            <i class="fab fa-stripe text-sm md:text-base"></i>
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="text-sm md:text-base">Pay with Stripe</span>
                                            <span class="text-lg md:text-xl font-bold">€{{ number_format($order->total, 2) }}</span>
                                        </div>
                                        <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 group-hover:translate-x-1 transition-all duration-300">
                                            <i class="fas fa-external-link-alt text-xs md:text-sm"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Hover Background -->
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </button>
                        </div>

        <!-- Security Notice -->
        <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-600">
            <i class="fas fa-shield-alt text-green-600"></i>
            <span>Redirects to Stripe's secure checkout page</span>
        </div>
    </div>
        
    @elseif($selectedProvider === 'paypal')
        <!-- PayPal Payment -->
        <div class="text-center">
                        <div class="mb-6">
                            <h4 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">Pay with PayPal</h4>
                            <p class="text-gray-600 text-base md:text-lg">You will be redirected to PayPal to complete your payment securely</p>
                        </div>
            
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
                                    Pay with PayPal - €{{ number_format($order->total, 2) }}
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
        </div>
    </div>
</div>

