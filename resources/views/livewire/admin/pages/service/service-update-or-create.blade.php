@php
    use App\Enums\BooleanEnum;
    use \App\Helpers\Constants;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="col-span-2 grid grid-cols-1 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')"
                             wire:model.blur="title"
                             required
                    />
                    <x-input :label="trans('validation.attributes.description')"
                             wire:model.blur="description"
                             required
                    />
                    <x-admin.shared.tinymce wire:model.blur="body"/>
                </div>
            </x-card>

            <x-card :title="trans('general.page_sections.seo_options')" shadow separator progress-indicator="submit">
                <x-admin.shared.seo-options class="lg:grid-cols-1"/>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload
                        default_image="{{ $model->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}"
                    />
                </x-card>

                {{-- NEW: Icon picker --}}
                <x-card title="Icons" shadow separator progress-indicator="submit" class="mt-5">
                    @error('icon')
                        <div class="mb-3 text-sm text-red-600">{{ $message }}</div>
                    @enderror

                    @if(!empty($this->icons))
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"
                             role="radiogroup" aria-label="Icon choices" wire:key="icon-picker">
                            @foreach($this->icons as $file)
                                @php
                                    $base   = pathinfo($file, PATHINFO_FILENAME);
                                    $id     = 'icon-'.Str::slug($base);
                                    $isSel  = ($icon === $file); // optional: server fallback
                                @endphp

                                <label for="{{ $id }}"
                                       role="radio"
                                       aria-checked="{{ $isSel ? 'true' : 'false' }}"
                                       class="group relative cursor-pointer rounded-2xl focus-within:outline-none
                                            focus-within:ring-2 focus-within:ring-teal-500 dark:focus-within:ring-teal-400
                                            focus-within:ring-offset-2 dark:focus-within:ring-offset-gray-900 transition"
                                >
                                    <!-- hidden radio drives the UI -->
                                    <input id="{{ $id }}"
                                           type="radio"
                                           name="service_icon"
                                           class="peer sr-only"
                                           wire:model.live="icon"
                                           value="{{ $file }}"
                                           @checked($isSel)
                                           wire:key="icon-radio-{{ $file }}" />

                                    <!-- card -->
                                    <div class="rounded-2xl p-4 select-none transition-all duration-200
                                        bg-white dark:bg-gray-900
                                        ring-1 ring-gray-200 dark:ring-gray-700
                                        hover:ring-teal-400 dark:hover:ring-teal-500 hover:shadow-sm
                                        peer-checked:ring-2 peer-checked:ring-teal-600 dark:peer-checked:ring-teal-400
                                        peer-checked:shadow-md
                                        peer-checked:bg-teal-50 dark:peer-checked:bg-teal-900/30"
                                    >
                                        <div class="flex flex-col items-center gap-2">
                                            <img
                                                src="{{ asset('assets/images/icon/'.$file) }}"
                                                alt="{{ $base }}"
                                                title="{{ $base }}"
                                                class="h-10 w-10 object-contain transition-transform duration-200
                                                        peer-checked:scale-105"
                                            />
                                            <div class="text-xs text-gray-700 dark:text-gray-300 text-center truncate w-full">
                                                {{ $base }}
                                            </div>
                                        </div>

                                        <!-- selection bar -->
                                        <div class="mt-3 h-1 rounded-full transition
                                              {{ $isSel
                                                  ? 'bg-teal-500 dark:bg-teal-400'
                                                  : 'bg-gray-100 dark:bg-gray-800 group-hover:bg-teal-200 dark:group-hover:bg-teal-700/50'
                                              }}">
                                        </div>

                                    </div>

                                    <!-- check badge -->
                                    <span class="absolute -top-1.5 -right-1.5 h-5 w-5 rounded-full
                                                 bg-teal-600 dark:bg-teal-500 text-white
                                                 flex items-center justify-center shadow
                                                 ring-2 ring-white dark:ring-gray-900
                                                 transition-all duration-200
                                                 opacity-0 scale-75
                                                 peer-checked:opacity-100 peer-checked:scale-100">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Keine Icons gefunden. Bitte legen Sie SVGs unter') }}
                            <code class="text-gray-700 dark:text-gray-200">public/assets/images/icon</code>
                            {{ __('ab.') }}
                        </p>
                    @endif

                </x-card>

                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit" class="mt-5">
                    <x-admin.shared.published-config has-published-at="0"/>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions/>
</form>
