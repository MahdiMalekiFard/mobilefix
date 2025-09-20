<?php

namespace App\Livewire\User\Components;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class SupportChatBadge extends Component
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
        $this->isActive = request()->routeIs('user.chat.*');
    }

    public function updateUnreadCount(): void
    {
        $conversation = Conversation::where('user_id', Auth::id())->first();
        $this->unreadCount = $conversation
            ? $conversation->messages()->where('sender_type', 'admin')->where('is_read', false)->count()
            : 0;
    }

    #[On('admin-message-received')]
    public function adminMessageReceived(): void
    {
        try {
            logger('🔔 SupportChatBadge: adminMessageReceived called');
            $this->updateUnreadCount();
            logger('🔔 SupportChatBadge: unread count updated to ' . $this->unreadCount);
        } catch (\Exception $e) {
            logger('❌ SupportChatBadge error: ' . $e->getMessage());
        }
    }

    #[On('messages-marked-as-read')]
    public function messagesMarkedAsRead(): void
    {
        try {
            logger('🔔 SupportChatBadge: messagesMarkedAsRead called');
            $this->updateUnreadCount();
            logger('🔔 SupportChatBadge: unread count updated to ' . $this->unreadCount);
        } catch (\Exception $e) {
            logger('❌ SupportChatBadge error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user.components.support-chat-badge');
    }
}
