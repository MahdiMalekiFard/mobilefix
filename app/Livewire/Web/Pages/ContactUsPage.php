<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Actions\ContactUs\StoreContactUsAction;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ContactUsPage extends Component
{
    #[Rule('required|string|min:2|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    #[Rule('nullable|numeric|digits_between:6,20')]
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
                'name'    => $this->name,
                'email'   => $this->email,
                'phone'   => $this->phone,
                'subject' => $this->subject,
                'message' => $this->message,
            ]);

            $this->reset(['name', 'email', 'phone', 'subject', 'message']);
            $this->isSubmitted = true;

            session()->flash('success', 'Ihre Nachricht wurde erfolgreich gesendet! Wir melden uns in Kürze bei Ihnen.');
        } catch (Exception $e) {
            session()->flash('error', 'Beim Senden Ihrer Nachricht ist leider ein Fehler aufgetreten. Bitte versuchen Sie es erneut.');
        }
    }

    public function render()
    {
        return view('livewire.web.pages.contact-us-page')
            ->layout('components.layouts.web');
    }
}
