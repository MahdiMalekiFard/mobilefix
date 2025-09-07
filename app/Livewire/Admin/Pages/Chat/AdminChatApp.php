<?php

namespace App\Livewire\Admin\Pages\Chat;

use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class AdminChatApp extends BaseAdminComponent
{
    public ?int $selectedId    = null;
    public string $messageText = '';

    public function mount(?int $conversationId = null): void
    {
        $this->selectedId = $conversationId;
    }

    public function getConversationsProperty()
    {
        // Eager-load lastMessage + user and compute unread_count efficiently
        return Conversation::query()
            ->with([
                'user:id,name',
                'user.media',
                'lastMessage:id,conversation_id,body,created_at',
            ])
            ->withCount([
                'messages as unread_count' => function (Builder $q) {
                    $q->where('is_read', false)
                        ->where('sender_id', '!=', Auth::id());
                },
            ])
            ->orderByDesc('last_message_at')
            ->get();
    }

    public function getActiveConversationProperty(): ?Conversation
    {
        if ( ! $this->selectedId) {
            return null;
        }

        return Conversation::query()
            ->with([
                'user:id,name,email',
                'user.media',
            ])
            ->find($this->selectedId);
    }

    public function getMessagesProperty()
    {
        if ( ! $this->selectedId) {
            return collect();
        }

        return Message::query()
            ->where('conversation_id', $this->selectedId)
            ->orderBy('created_at')
            ->get();
    }

    public function open(int $conversationId): void
    {
        $this->selectedId = $conversationId;

        // mark others' messages as read when opening
        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // trigger front-end to scroll to bottom
        $this->dispatch('scroll-bottom');
    }

    #[On('message-sent')] // optional: after sending a message from another component
    public function refreshThread(): void
    {
        $this->dispatch('scroll-bottom');
    }

    public function send(): void
    {
        if ( ! $this->selectedId || trim($this->messageText) === '') {
            return;
        }

        $msg = Message::create([
            'conversation_id' => $this->selectedId,
            'sender_id'       => auth()->id(),
            'sender_type'     => 'admin',
            'body'            => $this->messageText,
            'is_read'         => false,
        ]);

        // update conversation pointers
        $conv = Conversation::find($this->selectedId);
        $conv->update([
            'last_message_id' => $msg->id,
            'last_message_at' => now(),
        ]);

        $this->messageText = '';
        $this->dispatch('scroll-bottom');
        $this->dispatch('message-sent'); // if other widgets listen
    }

    public function render()
    {
        return view('livewire.admin.pages.chat.admin-chat-app', [
            'conversations' => $this->conversations,
        ]);
    }
}
