@props([
    'label' => '',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'hint' => '',
    'error' => null,
    'step' => null,
    'min' => null,
    'max' => null,
    'multiple' => false,
    'accept' => null,
])

@php
    $id = $attributes->get('id', 'form-input-' . uniqid());
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

    <div x-data="{ focused: false, showPassword: false }" class="relative">
    <input 
        @if($type === 'password')
            x-bind:type="showPassword ? 'text' : 'password'"
        @else
            type="{{ $type }}"
        @endif
        id="{{ $id }}"
        {{ $wireModel }}
        placeholder="{{ $placeholder }}"
        @if($step) step="{{ $step }}" @endif
        @if($min) min="{{ $min }}" @endif
        @if($max) max="{{ $max }}" @endif
        @if($multiple) multiple @endif
        @if($accept) accept="{{ $accept }}" @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($required) required @endif
        aria-invalid="{{ $error ? 'true' : 'false' }}"
        class="block w-full h-10 px-3 text-sm rounded-lg shadow-sm
            bg-white text-gray-900 placeholder:text-gray-400
            border border-gray-300 ring-1 ring-inset ring-gray-300
            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
            disabled:bg-gray-100 disabled:text-gray-500 disabled:placeholder:text-gray-400
            disabled:cursor-not-allowed disabled:ring-gray-200 disabled:border-gray-200
            read-only:bg-gray-50 read-only:text-gray-500 read-only:cursor-not-allowed
            dark:bg-gray-900 dark:text-white dark:placeholder-gray-400 dark:border-gray-600
            dark:disabled:bg-gray-800 dark:read-only:bg-gray-800
            @if($error) border-red-300 ring-red-300 focus:ring-red-500 focus:border-red-500 @endif
            @if($type === 'password') pr-10 @endif"
        {{ $attributes->except(['class', 'wire:model']) }}
    />
        
        @if($type === 'file')
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </div>
        @endif

        @if($type === 'password')
            <button 
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                :aria-label="showPassword ? 'Hide password' : 'Show password'"
            >
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
            </svg>
            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            </button>
        @endif
    </div>

    @if($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>