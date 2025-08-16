<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\ContactUs;

use App\Models\ContactUs;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class ContactUsView extends Component
{
    public ContactUs $contactUs;

    public function mount(ContactUs $contactUs)
    {
        $this->contactUs = $contactUs;
        
        // Mark as read when viewing
        if (!$this->contactUs->is_read) {
            $this->contactUs->markAsRead();
        }
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['link' => route('admin.contact-us.index'), 'label' => trans('contactUs.model')],
            ['label' => 'View Message'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.pages.contactUs.contact-us-view');
    }
}
