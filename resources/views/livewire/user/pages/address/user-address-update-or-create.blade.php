@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4">
            <x-input :label="trans('address.title')"
                     wire:model="title"
                     placeholder="Home, Office, etc."
                     :error="$errors->first('title')"
            />
            <x-textarea :label="trans('address.address')"
                        wire:model="address"
                        placeholder="Enter complete address including street, city, state, postal code"
                        rows="4"
                        :error="$errors->first('address')"
            />
            <x-toggle :label="trans('address.is_default')" 
                     wire:model="is_default" 
                     hint="Set as default delivery address"
                     right/>
        </div>
    </x-card>

    <x-admin.shared.form-actions/>
</form>
