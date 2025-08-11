<!-- Address Modal Root Container -->
<div>
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" 
         wire:click.self="closeModal">
        <div class="bg-white rounded-3xl p-6 md:p-8 w-full max-w-md mx-auto shadow-2xl"
             wire:click.stop>
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Add New Address</h3>
                        <p class="text-gray-600 text-sm">Enter your delivery address details</p>
                    </div>
                </div>
                <button type="button" 
                        wire:click="closeModal"
                        class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-times text-gray-600 text-sm"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form wire:submit="submit" class="space-y-4">
                <!-- Title Field -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Address Title *
                    </label>
                    <input type="text" 
                           id="title"
                           wire:model="title"
                           placeholder="Home, Office, etc."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address Field -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Complete Address *
                    </label>
                    <textarea id="address"
                              wire:model="address"
                              rows="4"
                              placeholder="Enter complete address including street, city, state, postal code"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 resize-none @error('address') border-red-500 @enderror"></textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Default Address Toggle -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" 
                           id="is_default"
                           wire:model="is_default"
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="is_default" class="text-sm font-medium text-gray-700">
                        Set as default delivery address
                    </label>
                </div>

                <!-- Modal Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" 
                            wire:click="closeModal"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="submit"
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="submit">Add Address</span>
                        <span wire:loading wire:target="submit" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Adding...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
