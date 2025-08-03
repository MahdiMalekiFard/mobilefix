@props([
    'options' => [], // like [['value' => 'pending', 'label' => 'Pending', 'badge' => ['text' => 'Pending', 'color' => 'warning']]]
    'label' => '',
    'model' => '',
    'placeholder' => 'Select an option...',
    'searchable' => true,
    'required' => false,
    'disabled' => false,
    'hint' => '',
    'error' => null,
])

@php
    $id = $attributes->get('id', 'badge-select-' . uniqid());
    $wireModel = $attributes->wire('model');
    $value = data_get($this, $wireModel->value()) ?? '';
    
    // Find selected option
    $selectedOption = collect($options)->firstWhere('value', $value);
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

    <div x-data="badgeSelect({
        options: {{ json_encode($options) }},
        model: @entangle($wireModel->value()),
        searchable: {{ $searchable ? 'true' : 'false' }},
        placeholder: '{{ $placeholder }}',
        disabled: {{ $disabled ? 'true' : 'false' }}
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
                <template x-if="!selectedOption">
                    <span class="text-gray-500" x-text="placeholder"></span>
                </template>
                <template x-if="selectedOption">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border"
                          :class="getBadgeClasses(selectedOption.badge?.color)">
                        <span x-text="selectedOption.badge?.text || selectedOption.label"></span>
                    </span>
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
                         :class="{ 'bg-indigo-50': selectedOption && selectedOption.value === option.value }">
                        <div class="flex items-center">
                            <!-- Badge -->
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border"
                                  :class="getBadgeClasses(option.badge?.color)">
                                <span x-text="option.badge?.text || option.label"></span>
                            </span>
                        </div>
                        
                        <!-- Selected Checkmark -->
                        <template x-if="selectedOption && selectedOption.value === option.value">
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
function badgeSelect(config) {
    return {
        open: false,
        search: '',
        model: config.model,
        options: config.options,
        searchable: config.searchable,
        placeholder: config.placeholder,
        disabled: config.disabled,
        
        get selectedOption() {
            return this.options.find(option => option.value === this.model);
        },
        
        get filteredOptions() {
            if (!this.searchable || !this.search) {
                return this.options;
            }
            return this.options.filter(option => 
                option.label.toLowerCase().includes(this.search.toLowerCase()) ||
                (option.badge?.text && option.badge.text.toLowerCase().includes(this.search.toLowerCase()))
            );
        },
        
        selectOption(option) {
            this.model = option.value;
            this.open = false;
            this.search = '';
        },
        
        getBadgeClasses(color) {
            const colorMap = {
                'warning': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'info': 'bg-blue-100 text-blue-800 border-blue-200',
                'danger': 'bg-red-100 text-red-800 border-red-200',
                'success': 'bg-green-100 text-green-800 border-green-200',
                'primary': 'bg-indigo-100 text-indigo-800 border-indigo-200',
            };
            return colorMap[color] || 'bg-gray-100 text-gray-800 border-gray-200';
        }
    }
}
</script>