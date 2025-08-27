@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4">
            <x-admin.shared.select :label="trans('order.user')"
                                   wire:model="user_id"
                                   :options="$users->map(function($user) { return ['value' => $user->id, 'label' => $user->name]; })"
                                   placeholder="{{ trans('order.select_user') }}"
                                   searchable
                                   :error="$errors->first('user_id')"
            />
            <x-admin.shared.form-input
                :label="trans('address.title')"
                wire:model="title"
                placeholder="Home, office, etc."
                :error="$errors->first('title')"
            />
            <x-admin.shared.textarea :label="trans('address.address')"
                                     wire:model="address"
                                     rows="4"
                                     placeholder="Enter the full address, including street, city, state, and zip code"
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
