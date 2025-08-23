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
                    <x-input :label="trans('validation.attributes.name')"
                             wire:model="name"
                    />
                    <x-input :label="trans('validation.attributes.job')"
                             wire:model="job"
                    />
                </div>
            </x-card>

            <x-card :title="trans('general.page_sections.social')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input label="YouTube"  wire:model.lazy="social.youtube"  placeholder="https://youtube.com/@yourchannel" />
                    <x-input label="Facebook" wire:model.lazy="social.facebook" placeholder="https://facebook.com/yourpage" />
                    <x-input label="X (Twitter)" wire:model.lazy="social.twitter" placeholder="https://x.com/yourhandle" />
                    <x-input label="LinkedIn" wire:model.lazy="social.linkedin" placeholder="https://linkedin.com/in/you" />
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload
                        default_image="{{ $model->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}"
                    />
                </x-card>

                <x-card :title="trans('general.page_sections.special')" shadow separator progress-indicator="submit" class="mt-5">
                    <div class="grid grid-cols-1 gap-4">
                        <x-toggle :label="trans('datatable.special')" wire:model="special" right/>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions/>
</form>
