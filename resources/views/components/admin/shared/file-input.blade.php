@props([
    'label' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'hint' => '',
    'error' => null,
    'multiple' => false,
    'accept' => null,
])

@php
    $id = $attributes->get('id', 'file-input-' . uniqid());
    $wireModel = $attributes->wire('model');
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

    <div x-data="{ 
        focused: false, 
        fileName: 'No file chosen',
        init() {
            // Check if there are already files selected (for edit mode)
            const input = this.$refs.fileInput;
            if (input && input.files && input.files.length > 0) {
                if (input.files.length === 1) {
                    this.fileName = input.files[0].name;
                } else {
                    this.fileName = input.files.length + ' files selected';
                }
            }
            
            // Watch for Livewire updates
            this.$watch('$wire.{{ $wireModel->value() }}', (value) => {
                if (!value || (Array.isArray(value) && value.length === 0)) {
                    this.fileName = 'No file chosen';
                }
            });
        }
    }" class="relative">
        <input 
            type="file"
            id="{{ $id }}"
            x-ref="fileInput"
            {{ $wireModel }}
            placeholder="{{ $placeholder }}"
            @if($multiple) multiple @endif
            @if($accept) accept="{{ $accept }}" @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($required) required @endif
            @focus="focused = true"
            @blur="focused = false"
            @change="
                if ($event.target.files.length > 0) {
                    if ($event.target.files.length === 1) {
                        fileName = $event.target.files[0].name;
                    } else {
                        fileName = $event.target.files.length + ' files selected';
                    }
                } else {
                    fileName = 'No file chosen';
                }
            "
            class="block w-full rounded-lg border border-gray-300 bg-white h-12 px-3 text-sm shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer dark:bg-gray-900 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
            :class="{
                'bg-gray-50 text-gray-500 cursor-not-allowed dark:bg-gray-800': {{ $disabled || $readonly ? 'true' : 'false' }},
                'border-red-300 ring-red-300': {{ $error ? 'true' : 'false' }},
                'hover:ring-gray-400': !{{ $disabled || $readonly ? 'true' : 'false' }} && !focused && !{{ $error ? 'true' : 'false' }},
                'ring-indigo-500 border-indigo-500': focused && !{{ $error ? 'true' : 'false' }},
                'ring-red-500 border-red-500': focused && {{ $error ? 'true' : 'false' }}
            }"
            {{ $attributes->except(['class', 'wire:model']) }}
        />
        
        <!-- Custom file display -->
        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400 italic" x-text="fileName"></div>
    </div>

    @if($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div> 