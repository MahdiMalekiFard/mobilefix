<?php

namespace App\Livewire\Admin\Pages\Chat;

use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class AdminChatShow extends BaseAdminComponent
{
    public Conversation $conversation;
    public string $newMessage = '';

    protected $rules = [
        'newMessage' => 'required|string|min:1|max:2000',
    ];

    public function mount(Conversation $conversation): void
    {
        $this->conversation = $conversation;
        $this->markUserMessagesAsRead();
    }

    public function sendMessage(): void
    {
        $this->validate();

        $message = new Message([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => Auth::id(),
            'sender_type'     => 'admin',
            'body'            => trim($this->newMessage),
        ]);
        $message->save();

        $this->conversation->update([
            'last_message_id' => $message->id,
            'last_message_at' => now(),
        ]);

        $this->newMessage = '';
    }

    public function markUserMessagesAsRead(): void
    {
        $this->conversation->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function render()
    {
        return view('livewire.admin.pages.chat.admin-chat-show', [
            'messages' => $this->conversation->messages()->orderBy('created_at')->get(),
        ]);
    }
}
