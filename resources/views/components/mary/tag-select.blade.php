<div
    x-data="{
        tags: @entangle($attributes->wire('model')),
        selected: @js($selected ?? []),
        input: '',
        open: false,
        focused: false,
        isReadonly: {{ json_encode($isReadonly()) }},
        isRequired: {{ json_encode($isRequired()) }},
        isDisabled: {{ json_encode($isDisabled()) }},
        options: @js($options),
        highlight: 0,

        init() {
            // If Livewire hasn’t hydrated yet, seed with selected
            if (!Array.isArray(this.tags) || this.tags.length === 0) {
                if (Array.isArray(this.selected) && this.selected.length) {
                    this.tags = [...this.selected];
                } else if (!Array.isArray(this.tags)) {
                    this.tags = [];
                }
            }
            this.options = [...new Set(this.options)];
        },

        filtered() {
            const q = this.input.trim().toLowerCase();
            const list = q
              ? this.options.filter(o => o.toLowerCase().includes(q))
              : this.options;
            return list.filter(o => !this.hasTag(o));
        },

        hasTag(name) {
            const n = name.toString().toLowerCase();
            return this.tags.some(t => t.toString().toLowerCase() === n);
        },

        add(name) {
            if (this.isReadonly || this.isDisabled) return;
            const v = name.toString().replace(/,/g, '').trim();
            if (v !== '' && !this.hasTag(v)) {
                this.tags.push(v);
            }
            this.input = '';
            this.highlight = 0;
            this.open = false;
            this.$nextTick(() => this.$refs.input?.focus());
        },

        remove(index) {
            if (this.isReadonly || this.isDisabled) return;
            this.tags.splice(index, 1);
        },

        clearAll() {
            if (this.isReadonly || this.isDisabled) return;
            this.tags = [];
        },

        onFocus() {
            if (this.isReadonly || this.isDisabled) return;
            this.focused = true;
            this.open = true;
        },

        onBlur(e) {
            if (!this.$el.contains(e.target)) {
                this.open = false;
                this.focused = false;
            }
        },

        onKeydown(e) {
            if (!this.open) this.open = true;
            const items = this.filtered();
            if (['ArrowDown','Tab'].includes(e.key)) {
                e.preventDefault();
                this.highlight = (this.highlight + 1) % Math.max(items.length, 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.highlight = (this.highlight - 1 + Math.max(items.length,1)) % Math.max(items.length,1);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (items.length > 0) {
                    this.add(items[this.highlight] ?? this.input);
                } else if (this.input.trim() !== '') {
                    this.add(this.input);
                }
            } else if (e.key === 'Escape') {
                this.open = false;
            }
        },
    }"
    x-on:click.window="onBlur($event)"
>
    <fieldset class="fieldset py-0">
        @if($label && !$inline)
            <legend class="fieldset-legend mb-0.5">
                {{ $label }}
                @if($attributes->get('required'))
                    <span class="text-error">*</span>
                @endif
            </legend>
        @endif

        <label @class(["floating-label" => $label && $inline])>
            @if ($label && $inline)
                <span class="font-semibold">{{ $label }}</span>
            @endif

            <div @class(["w-full", "join" => $prepend || $append])>
                @if($prepend) {{ $prepend }} @endif

                <label
                    @click="onFocus()"
                    @keydown="onKeydown($event)"
                    {{
                        $attributes->whereStartsWith('class')->class([
                            'input w-full h-fit pl-2.5 relative',
                            'join-item' => $prepend || $append,
                            'border-dashed' => $attributes->get('readonly') === true,
                            '!input-error' => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                        ])
                    }}
                >
                    @if($prefix)
                        <span class="label">{{ $prefix }}</span>
                    @endif

                    @if($icon)
                        <x-mary-icon :name="$icon" class="pointer-events-none w-4 h-4 opacity-40" />
                    @endif

                    <div class="w-full py-1 min-h-9.5 content-center text-wrap">
                        <span wire:key="tagselect-{{ $uuid }}">
                            <template x-for="(tag, index) in tags" :key="tag">
                                <span class="mary-tags-element cursor-pointer badge badge-soft m-0.5 !inline-block">
                                    <span x-text="tag"></span>
                                    <x-mary-icon @click.stop="remove(index)" x-show="!isReadonly && !isDisabled" name="o-x-mark" class="w-4 h-4 mb-0.5 hover:text-error" />
                                </span>
                            </template>
                        </span>

                        <span :class="(focused || tags.length) && 'hidden'" class="text-base-content/40">
                            {{ $attributes->get('placeholder') ?? __('Select or type tags') }}
                        </span>

                        <input
                            id="{{ $uuid }}"
                            type="text"
                            x-ref="input"
                            class="w-1 !inline-block"
                            x-model="input"
                            :required="isRequired && tags.length === 0"
                            :readonly="isReadonly"
                            :disabled="isDisabled"
                            @focus="onFocus()"
                            @input="open = true"
                        />
                    </div>

                    @if($clearable && !$isReadonly() && !$isDisabled())
                        <x-mary-icon @click.stop="clearAll()" x-show="tags.length" name="o-x-mark" class="cursor-pointer w-4 h-4 opacity-40"/>
                    @endif

                    @if($iconRight)
                        <x-mary-icon :name="$iconRight" class="pointer-events-none w-4 h-4 opacity-40" />
                    @endif

                    @if($suffix)
                        <span class="label">{{ $suffix }}</span>
                    @endif

                    <!-- Dropdown -->
                    <div
                        x-cloak
                        x-show="open"
                        class="absolute left-0 right-0 top-full z-50 mt-1 rounded-md border bg-base-100 shadow-md max-h-64 overflow-auto"
                    >
                        <template x-for="(opt, i) in filtered()" :key="opt">
                            <div
                                class="px-2 py-2 cursor-pointer hover:bg-base-200 flex items-center justify-between"
                                :class="{'bg-base-200': i === highlight}"
                                @mouseenter="highlight = i"
                                @click.prevent.stop="add(opt)"
                            >
                                <span x-text="opt"></span>
                                <span class="opacity-50 text-xs" x-show="hasTag(opt)">{{ __('Selected') }}</span>
                            </div>
                        </template>

                        <div
                            x-show="input.trim() !== '' && !hasTag(input) && !options.map(o=>o.toLowerCase()).includes(input.trim().toLowerCase())"
                            class="px-2 py-2 cursor-pointer hover:bg-base-200 border-t"
                            @click.prevent.stop="add(input.trim())"
                        >
                            {{ __('Create') }} “<span x-text="input"></span>”
                        </div>

                        <div
                            x-show="filtered().length === 0 && input.trim() === ''"
                            class="px-2 py-2 opacity-60"
                        >{{ __('No tags found') }}</div>
                    </div>
                </label>

                @if($append) {{ $append }} @endif
            </div>
        </label>

        @if(!$omitError && $errors->has($errorFieldName()))
            @foreach($errors->get($errorFieldName()) as $message)
                <div class="{{ $errorClass }}">{{ $message }}</div>
                @break($firstErrorOnly)
            @endforeach
        @endif

        @if($hint)
            <div class="{{ $hintClass }}">{{ $hint }}</div>
        @endif
    </fieldset>
</div>
