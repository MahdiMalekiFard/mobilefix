<?php

namespace App\Livewire\User\Pages\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserChatPage extends Component
{
    public ?Conversation $conversation = null;
    public string $newMessage = '';
    public Collection $chatMessages;

    protected $rules = [
        'newMessage' => 'required|string|min:1|max:2000',
    ];

    public function mount(): void
    {
        $this->chatMessages = new Collection();
        $this->ensureConversationExists();
        $this->loadMessages();
        $this->markOtherSideMessagesAsRead();
    }

    public function ensureConversationExists(): void
    {
        $userId = Auth::id();
        $this->conversation = Conversation::firstOrCreate(
            ['user_id' => $userId],
            ['last_message_at' => now()]
        );
    }

    public function loadMessages(): void
    {
        if (! $this->conversation) {
            return;
        }

        $this->chatMessages = $this->conversation
            ->messages()
            ->orderBy('created_at')
            ->get();
    }

    public function sendMessage(): void
    {
        $this->validate();

        if (! $this->conversation) {
            $this->ensureConversationExists();
        }

        $message = new Message([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => Auth::id(),
            'sender_type'     => 'user',
            'body'            => trim($this->newMessage),
        ]);
        $message->save();

        $this->conversation->update([
            'last_message_id' => $message->id,
            'last_message_at' => now(),
        ]);

        $this->newMessage = '';
        $this->loadMessages();
    }

    public function markOtherSideMessagesAsRead(): void
    {
        if (! $this->conversation) {
            return;
        }

        $this->conversation->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function render()
    {
        // Polling will be handled in Blade via wire:poll
        return view('livewire.user.pages.chat.user-chat-page')
            ->layout('components.layouts.user_panel');
    }
}


