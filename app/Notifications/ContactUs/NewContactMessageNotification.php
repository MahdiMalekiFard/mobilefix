<?php

declare(strict_types=1);

namespace App\Notifications\ContactUs;

use App\Models\ContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ContactUs $contactUs
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Message from ' . $this->contactUs->name)
            ->greeting('Hello Admin!')
            ->line('You have received a new contact message.')
            ->line('Name: ' . $this->contactUs->name)
            ->line('Email: ' . $this->contactUs->email)
            ->line('Subject: ' . ($this->contactUs->subject ?? 'No subject'))
            ->line('Message: ' . Str::limit($this->contactUs->message, 100))
            ->action('View Message', route('admin.contact-us.show', $this->contactUs))
            ->line('Please respond to this message as soon as possible.');
    }

    public function toArray($notifiable): array
    {
        return [
            'contact_us_id' => $this->contactUs->id,
            'name' => $this->contactUs->name,
            'email' => $this->contactUs->email,
            'subject' => $this->contactUs->subject,
            'message' => Str::limit($this->contactUs->message, 100),
        ];
    }
}
