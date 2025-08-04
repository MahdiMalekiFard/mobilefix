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
            @focus="focused = true"
            @blur="focused = false"
            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:outline-none resize-none dark:bg-gray-900 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
            :class="{
                'bg-gray-50 text-gray-500 cursor-not-allowed dark:bg-gray-800': {{ $disabled || $readonly ? 'true' : 'false' }},
                'border-red-300 ring-red-300': {{ $error ? 'true' : 'false' }},
                'hover:ring-gray-400': !{{ $disabled || $readonly ? 'true' : 'false' }} && !focused && !{{ $error ? 'true' : 'false' }},
                'ring-indigo-500 border-indigo-500': focused && !{{ $error ? 'true' : 'false' }},
                'ring-red-500 border-red-500': focused && {{ $error ? 'true' : 'false' }}
            }"
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