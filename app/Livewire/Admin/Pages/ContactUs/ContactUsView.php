<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\ContactUs;

use App\Models\ContactUs;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class ContactUsView extends Component
{
    public ContactUs $contactUs;
    public bool $wasUnread = false;

    public function mount(ContactUs $contactUs)
    {
        $this->contactUs = $contactUs;
        
        // Check if message was unread before marking as read
        if (!$this->contactUs->is_read->value) {
            $this->wasUnread = true;
            $this->contactUs->markAsRead();
            // Refresh the model to get the updated data
            $this->contactUs->refresh();
            
            // Show success message
            session()->flash('success', 'Message has been marked as read.');
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
