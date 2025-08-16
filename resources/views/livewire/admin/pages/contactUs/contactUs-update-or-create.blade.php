@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-input :label="'Name'"
                     wire:model="name"
                     required
            />
            <x-input :label="'Email'"
                     wire:model="email"
                     type="email"
                     required
            />
            <x-input :label="'Phone'"
                     wire:model="phone"
            />
            <x-input :label="'Subject'"
                     wire:model="subject"
            />
        </div>
        
        <div class="mt-4">
            <x-textarea :label="'Message'"
                        wire:model="message"
                        rows="5"
                        required
            />
        </div>
        
        @if($edit_mode)
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 mt-4">
            <x-toggle :label="'Mark as Read'" wire:model="is_read" right/>
        </div>
        @endif
    </x-card>

    <x-admin.shared.form-actions/>
</form>
