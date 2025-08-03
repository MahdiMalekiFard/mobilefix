@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    
    <!-- Basic Order Information -->
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit" class="mb-6 pb-8">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-admin.shared.form-input 
                :label="trans('order.order_number')"
                wire:model="order_number"
                placeholder="{{ trans('order.order_number') }}"
                required
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
                required
            />
            <x-admin.shared.form-input 
                :label="trans('order.user_email')"
                wire:model="user_email"
                placeholder="{{ trans('order.user_email') }}"
                type="email"
                required
            />
        </div>
    </x-card>

    <!-- Device & Brand Information -->
    <x-card :title="trans('order.device_brand_info')" shadow separator class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-select :label="trans('order.brand')"
                      wire:model="brand_id"
                      :options="$brands"
                      option-label="title"
                      option-value="id"
                      searchable
            />
            <x-select :label="trans('order.device')"
                      wire:model="device_id"
                      :options="$devices"
                      option-label="title"
                      option-value="id"
                      searchable
            />
        </div>
    </x-card>

    <!-- Address & Payment Information -->
    <x-card :title="trans('order.address_payment_info')" shadow separator class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-select :label="trans('order.address')"
                      wire:model="address_id"
                      :options="$addresses"
                      option-label="title"
                      option-value="id"
                      searchable
            />
            <x-select :label="trans('order.payment_method')"
                      wire:model="payment_method_id"
                      :options="$paymentMethods"
                      option-label="title"
                      option-value="id"
                      searchable
            />
        </div>
    </x-card>

    <!-- Problems -->
    <x-card :title="trans('order.problems')" shadow separator class="mb-6">
        <x-select :label="trans('order.problems')"
                  wire:model="selectedProblems"
                  :options="$problems"
                  option-label="title"
                  option-value="id"
                  multiselect
                  searchable
        />
    </x-card>

    <!-- Notes -->
    <x-card :title="trans('order.notes')" shadow separator class="mb-6">
        <div class="grid grid-cols-1 gap-4">
            <x-textarea :label="trans('order.user_note')"
                        wire:model="user_note"
                        rows="3"
                        placeholder="{{ trans('order.user_note_placeholder') }}"
            />
            <x-textarea :label="trans('order.admin_note')"
                        wire:model="admin_note"
                        rows="3"
                        placeholder="{{ trans('order.admin_note_placeholder') }}"
            />
        </div>
    </x-card>

    <!-- Media -->
    <x-card :title="trans('order.media')" shadow separator>
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div>
                <x-input :label="trans('order.images')"
                         type="file"
                         wire:model="images"
                         multiple
                         accept="image/*"
                />
                @if($images)
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @foreach($images as $image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-20 object-cover rounded">
                        @endforeach
                    </div>
                @endif
            </div>
            <div>
                <x-input :label="trans('order.videos')"
                         type="file"
                         wire:model="videos"
                         multiple
                         accept="video/*"
                />
                @if($videos)
                    <div class="mt-2">
                        @foreach($videos as $video)
                            <p class="text-sm text-gray-600">{{ $video->getClientOriginalName() }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-card>

    <x-admin.shared.form-actions/>
</form>
