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
    public string $search      = '';

    /** Pagination (latest chunk only) */
    public int $perPage        = 50;          // tune: 30–100
    public ?string $cursor     = null;     // current cursor (older)
    public ?string $nextCursor = null; // set when more older messages exist

    public function mount(?int $conversationId = null): void
    {
        $this->selectedId = $conversationId;
    }

    /** Sidebar list (users + last message + unread count) */
    public function getConversationsProperty()
    {
        return Conversation::query()
            ->with([
                'user:id,name,email',
                'user.media',
                'lastMessage:id,conversation_id,body,created_at',
            ])
            ->withCount([
                'messages as unread_count' => function (Builder $q) {
                    $q->where('is_read', false)
                        ->where('sender_id', '!=', Auth::id());
                },
            ])
            ->when(trim($this->search) !== '', function ($q) {
                $term = trim($this->search);
                $q->whereHas('user', function ($uq) use ($term) {
                    $uq->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('last_message_at')
            ->get();
    }

    /** Active conversation (header info) */
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

    /** Messages: only latest chunk via cursor pagination, then reverse for UI */
    public function getMessagesProperty()
    {
        if ( ! $this->selectedId) {
            return collect();
        }

        $page = Message::query()
            ->select(['id', 'conversation_id', 'sender_id', 'sender_type', 'body', 'created_at'])
            ->where('conversation_id', $this->selectedId)
            ->orderByDesc('id') // best for cursor pagination
            ->cursorPaginate($this->perPage, ['*'], 'cursor', $this->cursor);

        // pointer for “Load older”
        $this->nextCursor = optional($page->nextCursor())->encode();

        // Reverse so UI is oldest → newest
        return collect($page->items())->reverse()->values();
    }

    /** Open a conversation (reset cursor and mark others as read) */
    public function open(int $conversationId): void
    {
        $this->selectedId = $conversationId;
        $this->cursor     = null;
        $this->nextCursor = null;

        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $this->dispatch('scroll-bottom');
    }

    /** Load older chunk (adds older items at the top on next render) */
    public function loadOlder(): void
    {
        if ($this->nextCursor) {
            $this->cursor = $this->nextCursor;
        }
    }

    #[On('message-sent')]
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

        // update pointers on conversation
        $conv = Conversation::find($this->selectedId);
        $conv?->update([
            'last_message_id' => $msg->id,
            'last_message_at' => now(),
        ]);

        $this->messageText = '';
        $this->dispatch('scroll-bottom');
        $this->dispatch('message-sent');
    }

    public function render()
    {
        return view('livewire.admin.pages.chat.admin-chat-app', [
            'conversations' => $this->conversations,
        ]);
    }
}
