<!-- Address Selection Container -->
<div class="min-h-screen bg-slate-50 py-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 rounded-3xl p-6 md:p-8 mb-8 text-white shadow-2xl mx-4">
        <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6 text-center md:text-left">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-2xl"></i>
            </div>
            <div>
                <h3 class="text-2xl md:text-3xl font-bold mb-2">Select Delivery Address</h3>
                <p class="text-indigo-100 text-base md:text-lg">Choose where you want your device to be delivered</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4">
        @if(empty($userAddresses))
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-12 text-center shadow-xl">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-map-marker-alt text-3xl text-blue-600"></i>
                </div>
                <h4 class="text-2xl font-semibold text-gray-900 mb-3">No Addresses Found</h4>
                <p class="text-gray-700 text-lg mb-8 max-w-md mx-auto">You need to add a delivery address before proceeding with your order.</p>
                <a href="{{ route('user.address.create') }}" 
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    Add Your First Address
                </a>
            </div>
        @else
            <!-- Address Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                @foreach($userAddresses as $address)
                    <div class="group">
                        <div class="bg-white rounded-2xl border-2 {{ $selectedAddress && $selectedAddress->id == $address['id'] ? 'border-emerald-400 bg-gradient-to-br from-emerald-50 to-green-50 shadow-lg shadow-emerald-200/50' : 'border-slate-200 hover:border-indigo-300' }} p-6 cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden"
                             wire:click="selectAddress({{ $address['id'] }})">
                            
                            <!-- Selection Indicator -->
                            <div class="absolute top-4 right-4 {{ $selectedAddress && $selectedAddress->id == $address['id'] ? 'opacity-100 scale-100' : 'opacity-0 scale-50' }} transition-all duration-300">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                            </div>

                            <!-- Default Badge -->
                            @if($address['is_default'])
                                <div class="absolute top-4 left-4">
                                    <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide shadow-md">
                                        Default
                                    </span>
                                </div>
                            @endif

                            <!-- Card Content -->
                            <div class="pt-8">
                                <!-- Address Header -->
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-home text-gray-600 text-lg"></i>
                                    </div>
                                    <h5 class="text-xl font-semibold text-gray-900">{{ $address['title'] }}</h5>
                                </div>
                                
                                <!-- Address Content -->
                                <div class="mb-6">
                                    <p class="text-gray-700 leading-relaxed">{{ $address['address'] }}</p>
                                </div>

                                <!-- Address Footer -->
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 text-sm font-medium">Click to select</span>
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 group-hover:translate-x-1">
                                        <i class="fas fa-arrow-right text-sm"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Hover Effect -->
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </div>
                    </div>
                @endforeach

                <!-- Add New Address Card -->
                <div class="group">
                    <div class="bg-white border-2 border-dashed border-slate-300 rounded-2xl p-6 cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:border-indigo-400 hover:bg-gradient-to-br hover:from-slate-50 hover:to-indigo-50 min-h-[200px] flex items-center justify-center"
                         onclick="window.location.href='{{ route('user.address.create') }}'">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-plus text-white text-xl"></i>
                            </div>
                            <h5 class="text-lg font-semibold text-gray-900 mb-2">Add New Address</h5>
                            <p class="text-gray-700">Create a new delivery address</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue Button -->
            @if($selectedAddress)
                <div class="text-center pt-8 border-t border-slate-200">
                    <button wire:click="continueToPayment" 
                            class="group relative inline-flex items-center gap-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-10 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <span class="relative z-10">Continue to Payment</span>
                        <div class="relative z-10 w-6 h-6 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 group-hover:translate-x-1 transition-all duration-300">
                            <i class="fas fa-arrow-right text-sm"></i>
                        </div>
                        <!-- Hover Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>


