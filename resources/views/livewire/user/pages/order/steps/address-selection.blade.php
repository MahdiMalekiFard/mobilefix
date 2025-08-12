<!-- Address Selection Container -->
<div class="bg-slate-50 flex flex-col">
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800  rounded-3xl p-6 md:p-8 mb-6 text-white shadow-xl mx-4 mt-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
            <div class="flex items-center gap-4 md:gap-6 text-center md:text-left">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold mb-2">Select Delivery Address</h3>
                    <p class="text-indigo-100 text-base md:text-lg">Choose where you want your device to be delivered</p>
                </div>
            </div>

            @if($selectedAddress)
                <!-- Navigation Buttons in Header -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="continueToPayment" 
                            class="group relative inline-flex items-center gap-3 bg-white text-indigo-600 px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold text-sm md:text-base shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden cursor-pointer">
                        <span class="relative z-10">Continue to Payment</span>
                        <div class="relative z-10 w-4 h-4 md:w-5 md:h-5 bg-indigo-100 rounded-full flex items-center justify-center group-hover:bg-indigo-200 group-hover:translate-x-1 transition-all duration-300">
                            <i class="fas fa-arrow-right text-xs md:text-sm"></i>
                        </div>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 flex-1 flex flex-col">
        @if(empty($userAddresses))
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-8 md:p-12 text-center shadow-xl flex-1 flex flex-col items-center justify-center">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                    <i class="fas fa-map-marker-alt text-2xl md:text-3xl text-blue-600"></i>
                </div>
                <h4 class="text-xl md:text-2xl font-semibold text-gray-900 mb-2 md:mb-3">No Addresses Found</h4>
                <p class="text-gray-700 text-base md:text-lg mb-6 md:mb-8 max-w-md mx-auto">You need to add a delivery address before proceeding with your order.</p>
                <button wire:click="openAddressModal" 
                        class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-semibold text-base md:text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    Add Your First Address
                </button>
            </div>
        @else
            <!-- Address Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6 mb-6 flex-1">
                @foreach($userAddresses as $address)
                    <div class="group">
                        <div class="bg-white rounded-2xl border-2 {{ $selectedAddress && $selectedAddress->id == $address['id'] ? 'border-emerald-400 bg-gradient-to-br from-emerald-50 to-green-50 shadow-lg shadow-emerald-200/50' : 'border-slate-200 hover:border-indigo-300' }} p-4 md:p-6 cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden h-full flex flex-col"
                             wire:click="selectAddress({{ $address['id'] }})">
                            
                            <!-- Selection Indicator -->
                            <div class="absolute top-3 md:top-4 right-3 md:right-4 {{ $selectedAddress && $selectedAddress->id == $address['id'] ? 'opacity-100 scale-100' : 'opacity-0 scale-50' }} transition-all duration-300">
                                <div class="w-6 h-6 md:w-8 md:h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-check text-white text-xs md:text-sm"></i>
                                </div>
                            </div>

                            <!-- Default Badge -->
                            @if($address['is_default'])
                                <div class="absolute top-3 md:top-4 left-3 md:left-4">
                                    <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-2 md:px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide shadow-md">
                                        Default
                                    </span>
                                </div>
                            @endif

                            <!-- Card Content -->
                            <div class="pt-6 md:pt-8 flex-1 flex flex-col">
                                <!-- Address Header -->
                                <div class="flex items-center gap-3 md:gap-4 mb-3 md:mb-4">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-home text-gray-600 text-base md:text-lg"></i>
                                    </div>
                                    <h5 class="text-lg md:text-xl font-semibold text-gray-900">{{ $address['title'] }}</h5>
                                </div>
                                
                                <!-- Address Content -->
                                <div class="mb-4 md:mb-6 flex-1">
                                    <p class="text-gray-700 leading-relaxed text-sm md:text-base">{{ $address['address'] }}</p>
                                </div>

                                <!-- Address Footer -->
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-gray-600 text-xs md:text-sm font-medium">Click to select</span>
                                    <div class="w-6 h-6 md:w-8 md:h-8 bg-slate-100 rounded-full flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 group-hover:translate-x-1">
                                        <i class="fas fa-arrow-right text-xs md:text-sm"></i>
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
                    <div class="bg-white border-2 border-dashed border-slate-300 rounded-2xl p-4 md:p-6 cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:border-indigo-400 hover:bg-gradient-to-br hover:from-slate-50 hover:to-indigo-50 h-full flex items-center justify-center"
                        wire:click="openAddressModal">
                        <div class="text-center">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-plus text-white text-lg md:text-xl"></i>
                            </div>
                            <h5 class="text-base md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">Add New Address</h5>
                            <p class="text-gray-700 text-sm md:text-base">Create a new delivery address</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Create Address Modal (Tailwind v4-ready) -->
    <div
        x-data="{ open: @entangle('showAddressModal') }"
        x-show="open"
        x-cloak
        x-trap.noscroll="open"
        @keydown.escape.window="open = false"
        x-id="['address-modal-title','address-modal-desc']"
        role="dialog"
        aria-modal="true"
        :aria-labelledby="$id('address-modal-title')"
        class="fixed inset-0 z-[100] overflow-y-auto"
    >
        <!-- Backdrop -->
        <div
            x-show="open"
            x-transition.opacity
            @click="open = false"
            class="fixed inset-0 bg-black/50 supports-[backdrop-filter]:backdrop-blur-sm"
        ></div>

        <!-- Centering wrapper -->
        <div class="min-h-full flex items-end sm:items-center justify-center p-4">
            <!-- Panel -->
            <div
                x-show="open"
                @click.stop
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl ring-1 ring-black/5 border border-slate-100 dark:border-slate-800"
            >
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200/70 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-500/10">
                        <i class="fas fa-map-marker-alt text-indigo-600 dark:text-indigo-400"></i>
                    </span>
                        <div>
                            <h3 :id="$id('address-modal-title')" class="text-base sm:text-lg font-semibold text-slate-900 dark:text-slate-100">
                                Add New Address
                            </h3>
                            <p :id="$id('address-modal-desc')" class="text-sm text-slate-500 dark:text-slate-400">
                                Save it to your profile for faster checkout.
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="open = false"
                        aria-label="Close"
                        class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Form -->
                <form
                    wire:submit.prevent="createAddress"
                    class="px-6 py-5 space-y-5"
                    x-ref="form"
                    x-effect="open && $nextTick(() => $refs.title?.focus())"
                >
                    <!-- Title -->
                    <div>
                        <label for="addr-title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Title
                        </label>
                        <input
                            id="addr-title"
                            x-ref="title"
                            type="text"
                            wire:model.defer="newAddress.title"
                            placeholder="Home / Office"
                            autocomplete="section-address nickname"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 px-4 py-2.5 shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus:border-indigo-500"
                        />
                        @error('newAddress.title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="addr-lines" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Address
                        </label>
                        <textarea
                            id="addr-lines"
                            rows="3"
                            wire:model.defer="newAddress.address"
                            placeholder="Street, building, apartment..."
                            autocomplete="section-address street-address"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 px-4 py-3 shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus:border-indigo-500"
                        ></textarea>
                        @error('newAddress.address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="open = false"
                            class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="createAddress"
                            class="relative inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow-sm hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 disabled:opacity-60"
                        >
                            <span wire:loading.remove wire:target="createAddress">Save Address</span>
                            <span wire:loading wire:target="createAddress" class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity=".25" stroke-width="4" />
                                <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                            </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
