<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\ContactUs;

use App\Livewire\Admin\BaseAdminComponent;
use App\Models\ContactUs;
use Livewire\Attributes\Computed;

final class ContactUsView extends BaseAdminComponent
{
    public ContactUs $contactUs;
    public bool $wasUnread = false;

    public function mount(ContactUs $contactUs)
    {
        $this->contactUs = $contactUs;

        if ( ! $this->contactUs->is_read->value) {
            $this->wasUnread = true;
            $this->contactUs->markAsRead();
            $this->contactUs->refresh();
        }
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['link'  => route('admin.contact-us.index'), 'label' => trans('contactUs.model')],
            ['label' => 'View Message'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.pages.contactUs.contact-us-view');
    }
}
