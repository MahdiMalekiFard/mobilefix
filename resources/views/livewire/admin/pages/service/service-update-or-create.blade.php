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
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($this->icons as $file)
                                @php $id = 'icon-'.Str::slug(pathinfo($file, PATHINFO_FILENAME)); @endphp

                                <label for="{{ $id }}" class="group cursor-pointer rounded-2xl p-4 bg-white shadow-sm ring-1 ring-gray-200 hover:ring-teal-400 transition">
                                    <input id="{{ $id }}" name="service_icon" type="radio" class="sr-only" wire:model.live="icon" value="{{ $file }}" wire:key="icon-radio-{{ $file }}"/>
                                    <div class="flex flex-col items-center gap-2">
                                        <img
                                            src="{{ asset('assets/images/icon/'.$file) }}"
                                            alt="{{ pathinfo($file, PATHINFO_FILENAME) }}"
                                            class="h-10 w-10"
                                        />
                                        <div class="text-xs text-gray-600 truncate w-full text-center">
                                            {{ pathinfo($file, PATHINFO_FILENAME) }}
                                        </div>
                                    </div>
                                    <div
                                        @class([
                                            'mt-3 h-1 rounded-full transition-all',
                                            $icon === $file ? 'bg-teal-500' : 'bg-gray-100 group-hover:bg-teal-200'
                                        ])
                                    ></div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">
                            {{ __('Keine Icons gefunden. Bitte legen Sie SVGs unter') }}
                            <code>public/assets/images/icon</code> {{ __('ab.') }}
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
