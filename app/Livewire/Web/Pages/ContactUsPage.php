<?php

namespace App\Livewire\Web\Pages;

use App\Actions\ContactUs\StoreContactUsAction;
use Livewire\Component;
use Livewire\Attributes\Rule;

class ContactUsPage extends Component
{
    #[Rule('required|string|min:2|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    #[Rule('nullable|string|max:20')]
    public ?string $phone = null;

    #[Rule('nullable|string|max:255')]
    public ?string $subject = null;

    #[Rule('required|string|min:10|max:1000')]
    public string $message = '';

    public bool $isSubmitted = false;

    public function submitForm(StoreContactUsAction $storeAction)
    {
        $this->validate();

        try {
            $storeAction->handle([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'subject' => $this->subject,
                'message' => $this->message,
            ]);

            $this->reset(['name', 'email', 'phone', 'subject', 'message']);
            $this->isSubmitted = true;

            session()->flash('success', 'Your message has been sent successfully! We will get back to you soon.');
        } catch (\Exception $e) {
            session()->flash('error', 'Sorry, there was an error sending your message. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.web.pages.contact-us-page')
            ->layout('components.layouts.web');
    }
}
