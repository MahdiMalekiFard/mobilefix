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
    public string $messageText         = '';
    public Collection $chatMessages;
    public bool $adminIsTyping = false;

    public function mount(): void
    {
        $this->chatMessages = new Collection;
        $this->ensureConversationExists();
        $this->loadMessages();
        $this->markOtherSideMessagesAsRead();
    }

    public function ensureConversationExists(): void
    {
        $userId             = Auth::id();
        $this->conversation = Conversation::firstOrCreate(
            ['user_id' => $userId],
            ['last_message_at' => now()]
        );
    }

    public function loadMessages(): void
    {
        if ( ! $this->conversation) {
            return;
        }

        $this->chatMessages = $this->conversation
            ->messages()
            ->orderBy('created_at')
            ->get();
    }

    public function send(): void
    {
        if (trim($this->messageText) === '') {
            return;
        }

        if ( ! $this->conversation) {
            $this->ensureConversationExists();
        }

        $msg = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => auth()->id(),
            'sender_type'     => 'user',
            'body'            => $this->messageText,
            'is_read'         => false,
        ]);

        // update conversation pointers
        $this->conversation->update([
            'last_message_id' => $msg->id,
            'last_message_at' => now(),
        ]);

        $this->messageText = '';
        $this->loadMessages();
        $this->dispatch('message-sent');
    }

    public function markOtherSideMessagesAsRead(): void
    {
        if ( ! $this->conversation) {
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

    public function getListeners(): array
    {
        return [
            'echo:conversation.' . ($this->conversation?->id ?? 'none') . ',MessageSent' => 'messageReceived',
        ];
    }

    public function messageReceived(): void
    {
        // Refresh messages when a new message is received via broadcasting
        $this->loadMessages();
    }

    public function render()
    {
        // Reduced polling frequency since we'll use events for real-time updates
        return view('livewire.user.pages.chat.user-chat-page')
            ->layout('components.layouts.user_panel', ['external_class' => 'p-0 h-full overflow-hidden']);
    }
}
