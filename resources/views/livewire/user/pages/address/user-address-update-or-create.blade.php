@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4">
            <x-input :label="trans('address.title')"
                     wire:model="title"
                     placeholder="Zuhause, Büro usw."
                     :error="$errors->first('title')"
            />
            <x-textarea :label="trans('address.address')"
                        wire:model="address"
                        placeholder="Geben Sie die vollständige Adresse ein, einschließlich Straße, Ort, Bundesland und Postleitzahl"
                        rows="4"
                        :error="$errors->first('address')"
            />
            <x-toggle :label="trans('address.is_default')"
                     wire:model="is_default"
                     hint="Als Standardlieferadresse festlegen"
                     right/>
        </div>
    </x-card>

    <x-admin.shared.form-actions/>
</form>
