@props([
    'hasPublishedAt' => '1',
    'published' => false
])

<div class="grid grid-cols-1 gap-4" x-data="{ published: @entangle('published') }">
    <x-toggle :label="trans('validation.attributes.published')" wire:model="published" right/>
    
    <div x-show="!published" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95">
        @if($hasPublishedAt)
            <div class="space-y-2">
                <x-datetime :label="trans('validation.attributes.published_at')" wire:model="published_at" required />
                <p class="text-sm text-gray-600">
                    {{ trans('blog.help.published_at_explanation') }}
                </p>
            </div>
        @endif
    </div>
    
    <div x-show="published" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95">
        @if($hasPublishedAt)
            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-700">
                    {{ trans('blog.help.will_publish_immediately') }}
                </p>
            </div>
        @endif
    </div>
</div>
