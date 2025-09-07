<?php

namespace App\Livewire\Admin\Pages\Chat;

use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Conversation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminChatList extends BaseAdminComponent
{
    public string $search = '';

    public function getConversationsProperty(): LengthAwarePaginator
    {
        return Conversation::query()
            ->with(['user'])
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderByDesc('last_message_at')
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.admin.pages.chat.admin-chat-list', [
            'conversations' => $this->conversations,
        ]);
    }
}
