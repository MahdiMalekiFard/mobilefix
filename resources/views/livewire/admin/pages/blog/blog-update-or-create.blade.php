@php
    use App\Enums\BooleanEnum;
    use \App\Helpers\Constants;
@endphp
@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
                    <x-select
                        :label="trans('validation.attributes.category')"
                        wire:model="category_id"
                        :options="$categories"
                        required
                    />
                    <x-mary.tag-select
                        wire:model="tags"
                        :selected="$tags"
                        :key="'tag-select-'.$model->id"
                        label="Tags"
                        clearable
                    />
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

                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit" class="mt-5">
                    <div class="grid grid-cols-1 gap-4">
                        <x-admin.shared.published-config :published="$published"/>
                    </div>
                </x-card>
            </div>
        </div>

    </div>

    <x-admin.shared.form-actions/>
</form>
