<!-- Payment Selection Container -->
<div class="min-h-screen bg-slate-50 flex flex-col">
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 rounded-3xl p-6 md:p-8 mb-6 text-white shadow-2xl mx-4 mt-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
            <div class="flex items-center gap-4 md:gap-6 text-center md:text-left">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold mb-2">Choose Payment Method</h3>
                    <p class="text-indigo-100 text-base md:text-lg">Select your preferred payment method</p>
                </div>
            </div>
            
            <!-- Navigation Buttons in Header -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="goToStep(1)" 
                        class="group inline-flex items-center gap-3 bg-white/20 backdrop-blur-sm text-white border-2 border-white/30 px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold text-sm md:text-base shadow-md hover:bg-white/30 hover:border-white/50 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="w-4 h-4 md:w-5 md:h-5 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 group-hover:-translate-x-1 transition-all duration-300">
                        <i class="fas fa-arrow-left text-xs md:text-sm"></i>
                    </div>
                    Back to Address
                </button>
                
                @if($selectedProvider)
                    <button wire:click="goToStep(3)" 
                            class="group relative inline-flex items-center gap-3 bg-white text-indigo-600 px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold text-sm md:text-base shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden cursor-pointer">
                        <span class="relative z-10">Review Order</span>
                        <div class="relative z-10 w-4 h-4 md:w-5 md:h-5 bg-indigo-100 rounded-full flex items-center justify-center group-hover:bg-indigo-200 group-hover:translate-x-1 transition-all duration-300">
                            <i class="fas fa-arrow-right text-xs md:text-sm"></i>
                        </div>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 flex flex-col">
        @if(empty($availableProviders))
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-8 md:p-12 text-center shadow-xl flex-1 flex flex-col items-center justify-center">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-yellow-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                    <i class="fas fa-exclamation-triangle text-2xl md:text-3xl text-orange-600"></i>
                </div>
                <h4 class="text-xl md:text-2xl font-semibold text-gray-900 mb-2 md:mb-3">No Payment Methods Available</h4>
                <p class="text-gray-700 text-base md:text-lg mb-6 md:mb-8 max-w-md mx-auto">Please contact support to complete your payment.</p>
                <a href="mailto:support@example.com" 
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-semibold text-base md:text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <i class="fas fa-envelope"></i>
                    Contact Support
                </a>
            </div>
        @else
            <!-- Payment Methods Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @foreach($availableProviders as $provider)
                    <div class="group max-w-sm mx-auto">
                        <div class="bg-white rounded-2xl border-2 {{ $selectedProvider === $provider->value ? 'border-emerald-400 bg-gradient-to-br from-emerald-50 to-green-50 shadow-lg shadow-emerald-200/50' : 'border-slate-200 hover:border-indigo-300' }} p-4 md:p-6 cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden text-center h-48 md:h-52 flex flex-col justify-center">
                            
                            <!-- Selection Indicator -->
                            <div class="absolute top-3 md:top-4 right-3 md:right-4 {{ $selectedProvider === $provider->value ? 'opacity-100 scale-100' : 'opacity-0 scale-50' }} transition-all duration-300">
                                <div class="w-6 h-6 md:w-8 md:h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-check text-white text-xs md:text-sm"></i>
                                </div>
                            </div>

                            <!-- Provider Icon -->
                            <div class="mb-4 md:mb-6">
                                <div class="w-16 h-16 md:w-20 md:h-20 mx-auto rounded-full flex items-center justify-center {{ $provider->value === 'stripe' ? 'bg-gradient-to-br from-indigo-100 to-purple-100' : 'bg-gradient-to-br from-blue-100 to-blue-200' }} group-hover:scale-110 transition-transform duration-300">
                                    @if($provider->value === 'stripe')
                                        <i class="fab fa-stripe text-2xl md:text-4xl text-indigo-600"></i>
                                    @elseif($provider->value === 'paypal')
                                        <i class="fab fa-paypal text-2xl md:text-4xl text-blue-600"></i>
                                    @else
                                        <i class="{{ $provider->icon() }} text-2xl md:text-4xl text-gray-600"></i>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Security Features -->
                            <div class="flex justify-center gap-4 md:gap-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 md:w-6 md:h-6 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-emerald-600 text-xs"></i>
                                    </div>
                                    <span class="text-xs md:text-sm font-medium text-gray-700">SSL Encrypted</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 md:w-6 md:h-6 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-lock text-emerald-600 text-xs"></i>
                                    </div>
                                    <span class="text-xs md:text-sm font-medium text-gray-700">Secure</span>
                                </div>
                            </div>

                            <!-- Hover Effect -->
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>


