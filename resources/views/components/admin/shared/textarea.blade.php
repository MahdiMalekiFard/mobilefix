@props([
    'label' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'hint' => '',
    'error' => null,
    'rows' => 3,
])

@php
    $id = $attributes->get('id', 'textarea-' . uniqid());
    $wireModel = $attributes->wire('model');
    $value = data_get($this, $wireModel->value()) ?? '';
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div x-data="{ focused: false }" class="relative">
    <textarea
        id="{{ $id }}"
        {{ $wireModel }}
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($required) required @endif
        aria-invalid="{{ $error ? 'true' : 'false' }}"
        class="block w-full px-3 py-2 text-sm rounded-lg shadow-sm resize-none
            bg-white text-gray-900 placeholder:text-gray-400
            border border-gray-300 ring-1 ring-inset ring-gray-300
            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500

            disabled:bg-gray-100 disabled:text-gray-500 disabled:placeholder:text-gray-400
            disabled:cursor-not-allowed disabled:ring-gray-200 disabled:border-gray-200

            read-only:bg-gray-50 read-only:text-gray-500 read-only:cursor-not-allowed

            dark:bg-gray-900 dark:text-white dark:placeholder-gray-400 dark:border-gray-600
            dark:disabled:bg-gray-800 dark:read-only:bg-gray-800
            @if($error) border-red-300 ring-red-300 focus:ring-red-500 focus:border-red-500 @endif"
        {{ $attributes->except(['class', 'wire:model']) }}
    >{{ $value }}</textarea>
    </div>

    @if($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div> 