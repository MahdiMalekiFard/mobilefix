@props([
    'hasPublishedAt' => '1'
])

<div class="grid grid-cols-1 gap-4">
    <x-toggle :label="trans('validation.attributes.published')" wire:model="published" right/>
    @if($hasPublishedAt)
        <x-datetime :label="trans('validation.attributes.published_at')" wire:model="published_at" />
    @endif
</div>
