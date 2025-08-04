@props([
    'options' => [], // like [['value' => 1, 'label' => 'Option 1'], ['value' => 2, 'label' => 'Option 2']]
    'label' => '',
    'model' => '',
    'placeholder' => 'Select an option...',
    'searchable' => true,
    'required' => false,
    'disabled' => false,
    'hint' => '',
    'error' => null,
    'multiselect' => false,
])

@php
    $id = $attributes->get('id', 'select-' . uniqid());
    $wireModel = $attributes->wire('model');
    $value = data_get($this, $wireModel->value()) ?? '';
    
    // Find selected option(s)
    if ($multiselect) {
        $selectedOptions = collect($options)->whereIn('value', $value ?? [])->toArray();
    } else {
        $selectedOption = collect($options)->firstWhere('value', $value);
    }
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

    <div x-data="select({
        options: {{ json_encode($options) }},
        model: @entangle($wireModel->value()),
        searchable: {{ $searchable ? 'true' : 'false' }},
        placeholder: '{{ $placeholder }}',
        disabled: {{ $disabled ? 'true' : 'false' }},
        multiselect: {{ $multiselect ? 'true' : 'false' }}
    })" class="relative">
        
        <!-- Select Button -->
        <button 
            type="button" 
            @click="!disabled && (open = !open)"
            :disabled="disabled"
            class="relative w-full cursor-default rounded-lg bg-white h-10 px-3 pr-10 text-left shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm border border-gray-300"
            :class="{
                'bg-gray-50 text-gray-500 cursor-not-allowed': disabled,
                'hover:ring-gray-400': !disabled && !open,
                'ring-indigo-500': open
            }"
            @click.away="open = false"
        >
            <div class="flex items-center h-full">
                <template x-if="!multiselect && !selectedOption">
                    <span class="text-gray-500" x-text="placeholder"></span>
                </template>
                <template x-if="!multiselect && selectedOption">
                    <span class="text-gray-900" x-text="selectedOption.label"></span>
                </template>
                <template x-if="multiselect">
                    <div class="flex flex-wrap gap-1 min-h-0">
                        <template x-if="selectedOptions.length === 0">
                            <span class="text-gray-500" x-text="placeholder"></span>
                        </template>
                        <template x-for="option in selectedOptions" :key="option.value">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                <span x-text="option.label"></span>
                                <button type="button" @click.stop="removeOption(option)"
                                    class="ml-1 inline-flex text-indigo-400 hover:text-indigo-600">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>
                </template>
            </div>
            
            <!-- Dropdown Icon -->
            <span class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        <!-- Dropdown -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
            
            <!-- Search Input -->
            <template x-if="searchable">
                <div class="px-3 py-2 border-b border-gray-200">
                    <input 
                        type="text" 
                        x-model="search" 
                        @click.stop
                        class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search options..."
                    />
                </div>
            </template>

            <!-- Options -->
            <div class="py-1">
                <template x-for="option in filteredOptions" :key="option.value">
                    <div @click="selectOption(option)" 
                         class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-gray-100"
                         :class="{ 'bg-indigo-50': isSelected(option) }">
                        <div class="flex items-center">
                            <template x-if="multiselect">
                                <div class="flex items-center h-5 mr-3">
                                    <input type="checkbox" :checked="isSelected(option)"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                        @click.stop="selectOption(option)">
                                </div>
                            </template>
                            <span class="text-gray-900" x-text="option.label"></span>
                        </div>
                        
                        <!-- Selected Checkmark (for single select) -->
                        <template x-if="!multiselect && selectedOption && selectedOption.value === option.value">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </template>
                    </div>
                </template>
                
                <!-- No Results -->
                <template x-if="filteredOptions.length === 0">
                    <div class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-500">
                        No options found
                    </div>
                </template>
            </div>
        </div>
    </div>

    @if($hint)
        <p class="mt-1 text-sm text-gray-500">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>

<script>
function select(config) {
    return {
        open: false,
        search: '',
        model: config.model,
        options: config.options,
        searchable: config.searchable,
        placeholder: config.placeholder,
        disabled: config.disabled,
        multiselect: config.multiselect,
        
        get selectedOption() {
            if (this.multiselect) return null;
            return this.options.find(option => option.value === this.model);
        },
        
        get selectedOptions() {
            if (!this.multiselect) return [];
            if (!this.model || !Array.isArray(this.model)) return [];
            return this.options.filter(option => this.model.includes(option.value));
        },
        
        get filteredOptions() {
            if (!this.searchable || !this.search) {
                return this.options;
            }
            return this.options.filter(option => 
                option.label.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        
        isSelected(option) {
            if (this.multiselect) {
                return this.model && this.model.includes(option.value);
            } else {
                return this.model === option.value;
            }
        },
        
        selectOption(option) {
            if (this.multiselect) {
                if (this.isSelected(option)) {
                    this.removeOption(option);
                } else {
                    this.addOption(option);
                }
            } else {
                this.model = option.value;
                this.open = false;
                this.search = '';
            }
        },
        
        addOption(option) {
            if (!this.model) this.model = [];
            if (!Array.isArray(this.model)) this.model = [this.model];
            this.model.push(option.value);
        },
        
        removeOption(option) {
            if (this.model && Array.isArray(this.model)) {
                this.model = this.model.filter(value => value !== option.value);
            }
        }
    }
}
</script>