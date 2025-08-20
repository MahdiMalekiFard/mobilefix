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
                    <x-select
                        :label="trans('validation.attributes.brand')"
                        wire:model="brand_id"
                        :options="$brands"
                        required
                    />
                    <x-input :label="trans('validation.attributes.title')"
                             wire:model.blur="title"
                    />
                    <x-textarea :label="trans('validation.attributes.description')"
                                wire:model.blur="description"
                    />
                </div>
            </x-card>

            <x-card :title="trans('general.page_sections.seo_options')" shadow separator progress-indicator="submit">
                <x-admin.shared.seo-options class="lg:grid-cols-1"/>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit" class="mt-5">
                    <div class="grid grid-cols-1 gap-4">
                        <x-toggle :label="trans('datatable.status')" wire:model="published" right/>
                    </div>
                </x-card>

                <x-card :title="trans('general.page_sections.ordering')" shadow separator progress-indicator="submit" class="mt-5">
                    <div class="grid grid-cols-1 gap-4">
                        <x-input :label="trans('datatable.ordering')" wire:model="ordering" type="number"/>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions/>
</form>
