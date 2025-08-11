<!-- Payment Selection Container -->
<div class="min-h-screen bg-slate-50 py-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 rounded-3xl p-6 md:p-8 mb-8 text-white shadow-2xl mx-4">
        <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6 text-center md:text-left">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
            <div>
                <h3 class="text-2xl md:text-3xl font-bold mb-2">Choose Payment Method</h3>
                <p class="text-indigo-100 text-base md:text-lg">Select your preferred payment method</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4">
        @if(empty($availableProviders))
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-12 text-center shadow-xl">
                <div class="w-24 h-24 bg-gradient-to-br from-yellow-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-3xl text-orange-600"></i>
                </div>
                <h4 class="text-2xl font-semibold text-gray-900 mb-3">No Payment Methods Available</h4>
                <p class="text-gray-700 text-lg mb-8 max-w-md mx-auto">Please contact support to complete your payment.</p>
                <a href="mailto:support@example.com" 
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-envelope"></i>
                    Contact Support
                </a>
            </div>
        @else
            <!-- Payment Methods Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                @foreach($availableProviders as $provider)
                    <div class="group">
                        <div class="bg-white rounded-2xl border-2 {{ $selectedProvider === $provider->value ? 'border-emerald-400 bg-gradient-to-br from-emerald-50 to-green-50 shadow-lg shadow-emerald-200/50' : 'border-slate-200 hover:border-indigo-300' }} p-8 cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden text-center min-h-[280px] flex flex-col justify-center"
                             wire:click="selectPaymentProvider('{{ $provider->value }}')">
                            
                            <!-- Selection Indicator -->
                            <div class="absolute top-4 right-4 {{ $selectedProvider === $provider->value ? 'opacity-100 scale-100' : 'opacity-0 scale-50' }} transition-all duration-300">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                            </div>

                            <!-- Provider Icon -->
                            <div class="mb-6">
                                <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center {{ $provider->value === 'stripe' ? 'bg-gradient-to-br from-indigo-100 to-purple-100' : 'bg-gradient-to-br from-blue-100 to-blue-200' }} group-hover:scale-110 transition-transform duration-300">
                                    @if($provider->value === 'stripe')
                                        <i class="fab fa-stripe text-4xl text-indigo-600"></i>
                                    @elseif($provider->value === 'paypal')
                                        <i class="fab fa-paypal text-4xl text-blue-600"></i>
                                    @else
                                        <i class="{{ $provider->icon() }} text-4xl text-gray-600"></i>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Provider Title -->
                            <h5 class="text-2xl font-bold text-gray-900 mb-3">{{ $provider->title() }}</h5>
                            
                            <!-- Provider Description -->
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                @if($provider === App\Enums\PaymentProviderEnum::STRIPE)
                                    Pay securely with your credit or debit card
                                @elseif($provider === App\Enums\PaymentProviderEnum::PAYPAL)
                                    Pay with your PayPal account or credit card
                                @else
                                    Secure payment processing
                                @endif
                            </p>
                            
                            <!-- Security Features -->
                            <div class="flex justify-center gap-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-emerald-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">SSL Encrypted</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-lock text-emerald-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Secure</span>
                                </div>
                            </div>

                            <!-- Hover Effect -->
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payment Security Notice -->
            <div class="bg-white rounded-2xl p-6 mb-8 shadow-lg border border-slate-200">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-lg font-semibold text-gray-900 mb-2">Your payment is secure</h6>
                        <p class="text-gray-700 leading-relaxed">
                            We use industry-standard encryption to protect your payment information. 
                            Your payment details are processed securely and never stored on our servers.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex flex-col md:flex-row justify-between gap-4 pt-8 border-t border-slate-200">
                <button wire:click="goToStep(1)" 
                        class="group inline-flex items-center gap-3 bg-white text-gray-700 border-2 border-slate-300 px-8 py-4 rounded-full font-semibold text-lg shadow-md hover:shadow-lg hover:-translate-y-1 hover:border-slate-400 transition-all duration-300">
                    <div class="w-6 h-6 bg-slate-100 rounded-full flex items-center justify-center group-hover:bg-slate-200 group-hover:-translate-x-1 transition-all duration-300">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </div>
                    Back to Address
                </button>
                
                @if($selectedProvider)
                    <button wire:click="goToStep(3)" 
                            class="group inline-flex items-center gap-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-10 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <span class="relative z-10">Review Order</span>
                        <div class="relative z-10 w-6 h-6 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 group-hover:translate-x-1 transition-all duration-300">
                            <i class="fas fa-arrow-right text-sm"></i>
                        </div>
                        <!-- Hover Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>


