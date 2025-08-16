@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="col-span-2 grid grid-cols-1 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <x-input :label="trans('validation.attributes.title')"
                            wire:model="title"
                    />
                    <x-input :label="trans('validation.attributes.description')"
                            wire:model="description"
                    />
                    <x-input :label="trans('validation.attributes.min_price')"
                            wire:model="min_price"
                    />
                    <x-input :label="trans('validation.attributes.max_price')"
                            wire:model="max_price"
                    />
                </div>
            </x-card>
        </div>
        <div class="col-span-1 grid grid-cols-1 gap-4">
            <x-card :title="trans('general.page_sections.status')" shadow separator progress-indicator="submit">
                <x-input :label="trans('validation.attributes.ordering')"
                            wire:model="ordering"
                    />
                <div class="mt-4">
                    <x-toggle :label="trans('datatable.status')" wire:model="published" right/>
                </div>
            </x-card>
        </div>
    </div>

    <x-admin.shared.form-actions/>
</form>
