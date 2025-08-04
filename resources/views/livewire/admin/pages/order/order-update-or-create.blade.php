@php use App\Enums\BooleanEnum; @endphp
<div>
    <form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    
    <!-- Basic Order Information -->
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit" class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-admin.shared.form-input 
                :label="trans('order.order_number')"
                wire:model="order_number"
                placeholder="{{ trans('order.order_number') }}"
                disabled
                readonly
                class="bg-gray-50 cursor-not-allowed"
                :helper="trans('order.order_number_auto_generated')"
            />
            <x-admin.shared.form-input 
                :label="trans('order.tracking_code')"
                wire:model="tracking_code"
                placeholder="{{ trans('order.tracking_code') }}"
                disabled
                readonly
            />
            <x-admin.shared.badge-select
                :label="trans('order.status')"
                wire:model="status"
                :options="$statusOptions"
                placeholder="{{ trans('order.select_status') }}"
                searchable
                required
            />
            <x-admin.shared.form-input 
                :label="trans('order.total')"
                wire:model="total"
                placeholder="0.00"
                type="number"
                min="0"
                step="0.01"
                required
                class="font-mono"
                x-data
                x-on:input="$el.value = Math.max(0, parseFloat($el.value) || 0)"
                :helper="trans('order.total_currency_hint')"
            />
            
        </div>
    </x-card>

    <x-card :title="trans('order.user_info')" shadow separator progress-indicator="submit" class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-admin.shared.form-input 
                :label="trans('order.user_email')"
                wire:model="user_email"
                placeholder="{{ trans('order.user_email') }}"
                type="email"
                required
            />
            <x-admin.shared.form-input 
                :label="trans('order.user_phone')"
                wire:model="user_phone"
                placeholder="{{ trans('order.user_phone') }}"
                type="tel"
            />
        </div>
    </x-card>

    <!-- Device & Brand Information -->
    <x-card :title="trans('order.device_brand_info')" shadow separator class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-admin.shared.select :label="trans('order.brand')"
                      wire:model="brand_id"
                      :options="$brands->map(function($brand) { return ['value' => $brand->id, 'label' => $brand->title]; })"
                      placeholder="{{ trans('order.select_brand') }}"
                      searchable
            />
            <x-admin.shared.select :label="trans('order.device')"
                      wire:model="device_id"
                      :options="$devices->map(function($device) { return ['value' => $device->id, 'label' => $device->title]; })"
                      placeholder="{{ trans('order.select_device') }}"
                      searchable
            />
        </div>
    </x-card>

    <!-- Address & Payment Information -->
    <x-card :title="trans('order.address_payment_info')" shadow separator class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-admin.shared.select :label="trans('order.address')"
                      wire:model="address_id"
                      :options="$addresses->map(function($address) { return ['value' => $address->id, 'label' => $address->title]; })"
                      placeholder="{{ trans('order.select_address') }}"
                      searchable
            />
            <x-admin.shared.select :label="trans('order.payment_method')"
                      wire:model="payment_method_id"
                      :options="$paymentMethods->map(function($paymentMethod) { return ['value' => $paymentMethod->id, 'label' => $paymentMethod->title]; })"
                      placeholder="{{ trans('order.select_payment_method') }}"
                      searchable
            />
        </div>
    </x-card>

    <!-- Problems -->
    <x-card :title="trans('order.problems')" shadow separator class="mb-6">
        <x-admin.shared.select :label="trans('order.problems')"
                  wire:model="selectedProblems"
                  :options="$problems->map(function($problem) { return ['value' => $problem->id, 'label' => $problem->title]; })"
                  placeholder="{{ trans('order.select_problems') }}"
                  searchable
                  multiselect
        />
    </x-card>

    <!-- Notes -->
    <x-card :title="trans('order.notes')" shadow separator class="mb-6">
        <div class="grid grid-cols-1 gap-4">
            <x-admin.shared.textarea :label="trans('order.user_note')"
                        wire:model="user_note"
                        rows="3"
                        placeholder="{{ trans('order.user_note_placeholder') }}"
            />
            <x-admin.shared.textarea :label="trans('order.admin_note')"
                        wire:model="admin_note"
                        rows="3"
                        placeholder="{{ trans('order.admin_note_placeholder') }}"
            />
        </div>
    </x-card>

    <!-- Media -->
    <x-card :title="trans('order.media')" shadow separator>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Images Section -->
            <div>
                <x-admin.shared.file-input :label="trans('order.images')"
                         wire:model="images"
                         multiple
                         accept="image/*"
                />
                
                <!-- Existing Images -->
                @if(count($existingImages) > 0)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Existing Images</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($existingImages as $image)
                                <div class="relative group">
                                    <img src="{{ $image['url'] }}" 
                                         alt="{{ $image['name'] }}" 
                                         class="w-full h-32 object-cover rounded border cursor-pointer hover:opacity-80 transition-opacity"
                                         onclick="openImageModal('{{ $image['url'] }}', '{{ $image['name'] }}')">
                                    <button type="button"
                                            wire:click="deleteImage({{ $image['id'] }})"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                            title="Delete image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b">
                                        <span class="truncate block">{{ $image['name'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- New Images Preview -->
                @if($images)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">New Images</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($images as $index => $image)
                                <div class="relative group">
                                    <img src="{{ $image->temporaryUrl() }}" 
                                         alt="Preview" 
                                         class="w-full h-32 object-cover rounded border cursor-pointer hover:opacity-80 transition-opacity"
                                         onclick="openImageModal('{{ $image->temporaryUrl() }}', '{{ $image->getClientOriginalName() }}')">
                                    <button type="button"
                                            wire:click="removeNewImage({{ $index }})"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                            title="Remove image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b">
                                        <span class="truncate block">{{ $image->getClientOriginalName() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Videos Section -->
            <div>
                <x-admin.shared.file-input :label="trans('order.videos')"
                         wire:model="videos"
                         multiple
                         accept="video/*"
                />
                
                <!-- Existing Videos -->
                @if(count($existingVideos) > 0)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Existing Videos</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($existingVideos as $video)
                                <div class="relative group">
                                    <div class="w-full h-32 bg-gray-100 rounded border cursor-pointer hover:bg-gray-200 transition-colors flex items-center justify-center"
                                         onclick="openVideoModal('{{ $video['url'] }}', '{{ $video['name'] }}')">
                                        <div class="text-center">
                                            <i class="fas fa-play-circle text-4xl text-blue-500 mb-2"></i>
                                            <p class="text-xs text-gray-600">{{ $video['name'] }}</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            wire:click="deleteVideo({{ $video['id'] }})"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                            title="Delete video">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- New Videos Preview -->
                @if($videos)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">New Videos</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($videos as $index => $video)
                                <div class="relative group">
                                    <div class="w-full h-32 bg-blue-100 rounded border cursor-pointer hover:bg-blue-200 transition-colors flex items-center justify-center"
                                         onclick="openVideoModal('{{ $video->temporaryUrl() }}', '{{ $video->getClientOriginalName() }}')">
                                        <div class="text-center">
                                            <i class="fas fa-play-circle text-4xl text-blue-500 mb-2"></i>
                                            <p class="text-xs text-gray-600">{{ $video->getClientOriginalName() }}</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            wire:click="removeNewVideo({{ $index }})"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                            title="Remove video">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-card>

    <x-admin.shared.form-actions/>
</form>

<!-- Image Modal (Outside the form to prevent form submission) -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-5xl max-h-full w-full h-full flex items-center justify-center">
        <button type="button" onclick="closeImageModal(event)" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">
            <i class="fas fa-times"></i>
        </button>
        <div class="relative w-full h-full flex items-center justify-center">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded shadow-2xl">
            <div class="absolute bottom-4 left-4 text-white text-sm bg-black bg-opacity-70 px-3 py-2 rounded-lg backdrop-blur-sm">
                <span id="modalImageName" class="font-medium"></span>
            </div>
        </div>
        <div class="absolute bottom-4 right-4 text-white text-xs bg-black bg-opacity-50 px-2 py-1 rounded">
            Press ESC to close
        </div>
    </div>
</div>

<!-- Video Modal (Outside the form to prevent form submission) -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-5xl max-h-full w-full h-full flex items-center justify-center">
        <button type="button" onclick="closeVideoModal(event)" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">
            <i class="fas fa-times"></i>
        </button>
        <div class="relative w-full h-full flex items-center justify-center">
            <video id="modalVideo" controls class="max-w-full max-h-full object-contain rounded shadow-2xl">
                Your browser does not support the video tag.
            </video>
            <div class="absolute bottom-4 left-4 text-white text-sm bg-black bg-opacity-70 px-3 py-2 rounded-lg backdrop-blur-sm">
                <span id="modalVideoName" class="font-medium"></span>
            </div>
        </div>
        <div class="absolute bottom-4 right-4 text-white text-xs bg-black bg-opacity-50 px-2 py-1 rounded">
            Press ESC to close
        </div>
    </div>
</div>

<script>
    function openImageModal(imageUrl, imageName) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('modalImageName').textContent = imageName;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal(event) {
        // Prevent any form submission
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openVideoModal(videoUrl, videoName) {
        const videoElement = document.getElementById('modalVideo');
        videoElement.src = videoUrl;
        document.getElementById('modalVideoName').textContent = videoName;
        document.getElementById('videoModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Auto-play the video when modal opens
        videoElement.play().catch(function(error) {
            console.log('Video autoplay failed:', error);
        });
    }

    function closeVideoModal(event) {
        // Prevent any form submission
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
        
        // Pause and reset the video
        const videoElement = document.getElementById('modalVideo');
        videoElement.pause();
        videoElement.currentTime = 0;
        
        document.getElementById('videoModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close image modal when clicking outside the image
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal(e);
        }
    });

    // Close video modal when clicking outside the video
    document.getElementById('videoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeVideoModal(e);
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal(e);
            closeVideoModal(e);
        }
    });

    // Prevent form submission when clicking modal elements
    document.getElementById('imageModal').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    document.getElementById('videoModal').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Additional safety: prevent any form submission from modals
    document.getElementById('imageModal').addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    document.getElementById('videoModal').addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });
</script>
</div>
