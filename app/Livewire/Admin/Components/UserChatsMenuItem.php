<?php

namespace App\Livewire\Admin\Components;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class UserChatsMenuItem extends Component
{
    public int $unreadCount = 0;
    public bool $isActive = false;

    public function mount(): void
    {
        $this->updateUnreadCount();
        $this->checkActiveState();
    }

    public function checkActiveState(): void
    {
        $this->isActive = request()->routeIs('admin.chat.*');
    }

    public function updateUnreadCount(): void
    {
        $this->unreadCount = Message::where('sender_type', 'user')
            ->where('is_read', false)
            ->count();
    }

    #[On('global-message-received')]
    public function globalMessageReceived(): void
    {
        try {
            logger('🔔 UserChatsMenuItem: globalMessageReceived called');
            $this->updateUnreadCount();
            logger('🔔 UserChatsMenuItem: unread count updated to ' . $this->unreadCount);
        } catch (\Exception $e) {
            logger('❌ UserChatsMenuItem error: ' . $e->getMessage());
        }
    }

    #[On('messages-marked-as-read')]
    public function messagesMarkedAsRead(): void
    {
        try {
            logger('🔔 UserChatsMenuItem: messagesMarkedAsRead called');
            $this->updateUnreadCount();
            logger('🔔 UserChatsMenuItem: unread count updated to ' . $this->unreadCount);
        } catch (\Exception $e) {
            logger('❌ UserChatsMenuItem error: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.admin.components.user-chats-menu-item');
    }
}
