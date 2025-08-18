@props([
    'label'      => null,
    'hint'       => null,
    'id'         => $attributes->get('id') ?? Str::uuid()->toString(),

    // Only date
    'dateFormat' => 'Y-m-d',   // format stored/sent to Livewire
    'altFormat'  => 'F j, Y',  // nice display (e.g., August 18, 2025)
    'allowInput' => true,
])

@php
    $boundProp = $attributes->wire('model')->value();

    if (!$boundProp) {
        throw new \RuntimeException('<x-admin.shared.datetime-flat> requires wire:model / wire:model.live.');
    }
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <div
        x-data="{
            fp: null,
            get modelName() { return '{{ $boundProp }}' },

            seedFromWire() {
                const val = this.$wire.get(this.modelName);
                if (this.fp) {
                    if (!val) {
                        this.fp.clear();
                    } else {
                        this.fp.setDate(val, false, '{{ $dateFormat }}');
                    }
                }
            },

            initPicker() {
                const input = this.$refs.input;
                this.fp = flatpickr(input, {
                    enableTime: false,          // 🚫 no time
                    dateFormat: '{{ $dateFormat }}',
                    altInput: true,
                    altFormat: '{{ $altFormat }}',
                    allowInput: {{ $allowInput ? 'true' : 'false' }},

                    onChange: (selectedDates, value) => {
                        this.$wire.set(this.modelName, value || null);
                    },
                    onClose: (selectedDates, value) => {
                        this.$wire.set(this.modelName, value || null);
                    },
                });

                this.$nextTick(() => this.seedFromWire());

                this.$watch(
                    () => this.$wire.get(this.modelName),
                    () => this.seedFromWire()
                );
            }
        }"
        x-init="initPicker()"
        wire:ignore
    >
        <input
            x-ref="input"
            id="{{ $id }}"
            type="text"
            {{ $attributes->except('wire:model')->merge([
                'class' => 'w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500'
            ]) }}
        />
    </div>

    @if($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif

    @error($boundProp)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
