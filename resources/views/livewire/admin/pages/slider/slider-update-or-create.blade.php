@php
    use App\Enums\BooleanEnum;
    use App\Helpers\Constants;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="col-span-2 grid grid-cols-1 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')"
                             wire:model="title"
                    />
                    <x-textarea :label="trans('validation.attributes.description')"
                                wire:model="description"
                    />
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload
                        default_image="{{ $model->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}"
                        crop_after_change="0"
                    />
                </x-card>

                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit" class="mt-5">
                    <div class="grid grid-cols-1 gap-4">
                        <x-toggle :label="trans('datatable.status')" wire:model="published" right/>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions/>
</form>
