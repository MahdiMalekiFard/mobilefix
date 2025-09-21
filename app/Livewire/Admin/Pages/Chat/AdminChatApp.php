<?php

namespace App\Livewire\Admin\Pages\Chat;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Helpers\BroadcastHelper;
use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class AdminChatApp extends BaseAdminComponent
{
    use WithFileUploads;

    public int $receivedCount  = 0;
    public ?int $selectedId    = null;
    public string $messageText = '';
    public string $search      = '';

    /** Pagination (latest chunk only) */
    public int $perPage        = 50;
    public ?string $cursor     = null;       // current cursor (older)
    public ?string $nextCursor = null;   // set when more older messages exist

    /** uploads */
    public array $uploads        = []; // array of Livewire temporary files
    public array $newUploads     = [];  // staging: last picked/dropped files
    public bool $groupItems      = true;
    public bool $compressImages  = false;
    public bool $isSending       = false; // loading state for file uploads
    public bool $userIsTyping    = false; // track user typing status

    protected function rules(): array
    {
        return [
            'messageText'    => ['nullable', 'string', 'max:5000'],
            'uploads'        => ['array', 'max:5'],
            'uploads.*'      => ['file', 'max:20480', 'mimes:jpg,jpeg,png,webp,gif,bmp,svg,pdf,txt,csv,doc,docx,xls,xlsx,ppt,pptx,zip,rar,7z,mp3,wav,ogg,mp4,avi,mov,wmv,webm,json,xml'],

            'newUploads'     => ['sometimes', 'array'],
            'newUploads.*'   => ['sometimes', 'file', 'max:20480', 'mimes:jpg,jpeg,png,webp,gif,bmp,svg,pdf,txt,csv,doc,docx,xls,xlsx,ppt,pptx,zip,rar,7z,mp3,wav,ogg,mp4,avi,mov,wmv,webm,json,xml'],

            'groupItems'     => ['boolean'],
            'compressImages' => ['boolean'],
        ];
    }

    /** Auto-trigger when files are chosen - Alpine.js handles modal opening */
    public function updatedUploads(): void
    {
        if ($this->selectedId && count($this->uploads ?? []) > 0) {
            $this->dispatch('focus-composer');
        }
    }

    /** When user picks/drops a new batch, append to main list and clear the staging input */
    public function updatedNewUploads(): void
    {
        // validate just this batch (optional but recommended for fast feedback)
        $this->validateOnly('newUploads');
        $this->validateOnly('newUploads.*');

        // merge old + new, enforce uniqueness by tmp filename, cap at 5
        $merged = collect($this->uploads)
            ->concat($this->newUploads)
            ->unique(fn ($f) => method_exists($f, 'getFilename') ? $f->getFilename() : spl_object_hash($f))
            ->take(5) // keep earliest first; drop extras beyond 5
            ->values()
            ->all();

        $this->uploads = $merged;
        $this->reset('newUploads');       // clear the input so next Add only contains new picks
    }

    /** Cancel: discard selected files */
    public function cancelUploads(): void
    {
        $this->uploads         = [];
        $this->reset('newUploads');
    }

    /** "Send" from modal */
    public function confirmSendFromModal(): void
    {
        $this->isSending = true;

        try {
            $this->send();
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            Log::error($e->getMessage());
        } finally {
            $this->isSending = false;
        }
    }

    public function removeUploadByName(string $filename): void
    {
        $this->uploads = collect($this->uploads)
            ->reject(fn ($f) => method_exists($f, 'getFilename') && $f->getFilename() === $filename)
            ->values()
            ->all();
    }

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
            ->with(['user:id,name,email', 'user.media'])
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
            ->orderByDesc('id')
            ->cursorPaginate($this->perPage, ['*'], 'cursor', $this->cursor);

        $this->nextCursor = optional($page->nextCursor())?->encode();

        return collect($page->items())->reverse()->values();
    }

    /** Open a conversation (reset cursor and mark others as read) */
    public function open(int $conversationId): void
    {
        $this->selectedId = $conversationId;
        $this->cursor     = null;
        $this->nextCursor = null;
        $this->uploads    = [];

        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Dispatch event to update all badge counts after marking messages as read
        $this->dispatch('messages-marked-as-read');

        $this->dispatch('scroll-bottom');
        $this->dispatch('focus-composer');
    }

    /** Close current conversation */
    public function closeConversation(): void
    {
        $this->selectedId = null;
        $this->cursor     = null;
        $this->nextCursor = null;
        $this->uploads    = [];
        $this->userIsTyping = false;
    }

    /** Load older chunk (adds older items at the top on next render) */
    public function loadOlder(): void
    {
        if ($this->nextCursor) {
            $this->cursor = $this->nextCursor;
            // let the blade JS preserve scroll
            $this->dispatch('older-loaded');
        }
    }

    #[On('message-sent')]
    public function refreshThread(): void
    {
        $this->dispatch('scroll-bottom');
    }

    public function messageReceived(): void
    {
        $this->cursor = null;

        // Force component refresh
        $this->skipRender = false;

        $this->dispatch('scroll-bottom');
    }

    public function userTypingReceived($event): void
    {
        try {
            // Only show typing indicator for other users (user in this case) and if we have an active conversation
            if ($this->selectedId && $event['user_type'] === 'user' && $event['user_id'] !== auth()->id()) {
                $this->userIsTyping = $event['is_typing'];
                $this->dispatch('scroll-bottom');
            }
        } catch (\Exception $e) {
            // Gracefully handle any errors to prevent DOM tree issues
            logger('AdminChatApp userTypingReceived error: ' . $e->getMessage());
        }
    }

    #[On('global-message-received')]
    public function globalMessageReceived(): void
    {
        // Refresh the conversations list to update unread counts and last messages
        // This is called when any new message is sent to any conversation
        
        // If a conversation is currently open, immediately mark any new messages 
        // from that conversation as read to prevent flicker effect
        if ($this->selectedId) {
            $updatedCount = Message::where('conversation_id', $this->selectedId)
                ->where('sender_id', '!=', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
            
            // If we marked any messages as read, update all badges
            if ($updatedCount > 0) {
                $this->dispatch('messages-marked-as-read');
            }
        }
        
        $this->skipRender = false;
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function send(): void
    {
        // Set loading state if sending from regular input (not modal)
        if ( ! $this->isSending) {
            $this->isSending = true;
        }

        $this->validate();

        if ( ! $this->selectedId) {
            $this->isSending = false;

            return;
        }

        // disallow truly empty messages
        if (trim($this->messageText) === '' && count($this->uploads) === 0) {
            $this->isSending = false;

            return;
        }

        // ========== CASE 1: Group items into one message ==========
        if ($this->groupItems) {
            $msg = Message::create([
                'conversation_id' => $this->selectedId,
                'sender_id'       => auth()->id(),
                'sender_type'     => 'admin',
                'body'            => $this->messageText,
                'is_read'         => false,
            ]);

            foreach ($this->uploads as $file) {
                $adder = $msg->addMedia($file->getRealPath())
                    ->usingFileName($file->getClientOriginalName());

                if ($this->compressImages && str_starts_with($file->getMimeType(), 'image/')) {
                    $adder->withCustomProperties(['compress' => true]);
                }

                $adder->toMediaCollection('attachments');
            }

            $lastMessageId = $msg->id;

            // Broadcast the message safely
            BroadcastHelper::safeBroadcast(new MessageSent($msg));
        }

        // ========== CASE 2: Separate messages ==========
        else {
            $lastMessageId = null;

            // 1. Send text first (if any)
            if (trim($this->messageText) !== '') {
                $textMsg = Message::create([
                    'conversation_id' => $this->selectedId,
                    'sender_id'       => auth()->id(),
                    'sender_type'     => 'admin',
                    'body'            => $this->messageText,
                    'is_read'         => false,
                ]);
                $lastMessageId = $textMsg->id;

                // Broadcast the text message safely
                BroadcastHelper::safeBroadcast(new MessageSent($textMsg));
            }

            // 2. Send each file as its own message
            foreach ($this->uploads as $file) {
                $fileMsg = Message::create([
                    'conversation_id' => $this->selectedId,
                    'sender_id'       => auth()->id(),
                    'sender_type'     => 'admin',
                    'body'            => null,
                    'is_read'         => false,
                ]);

                $adder = $fileMsg->addMedia($file->getRealPath())
                    ->usingFileName($file->getClientOriginalName());

                if ($this->compressImages && str_starts_with($file->getMimeType(), 'image/')) {
                    $adder->withCustomProperties(['compress' => true]);
                }

                $adder->toMediaCollection('attachments');
                $lastMessageId = $fileMsg->id;

                // Broadcast the file message safely
                BroadcastHelper::safeBroadcast(new MessageSent($fileMsg));
            }
        }

        // Update conversation pointers
        if ($lastMessageId) {
            Conversation::find($this->selectedId)?->update([
                'last_message_id' => $lastMessageId,
                'last_message_at' => now(),
            ]);
        }

        // reset
        $this->messageText = '';
        $this->uploads     = [];
        $this->isSending   = false;

        // show latest page again
        $this->cursor = null;

        $this->dispatch('scroll-bottom');
        $this->dispatch('message-sent');
    }

    public function startTyping(): void
    {
        if ($this->selectedId) {
            BroadcastHelper::safeBroadcast(new UserTyping(
                $this->selectedId,
                auth()->id(),
                'admin',
                auth()->user()->name,
                true
            ));
        }
    }

    public function stopTyping(): void
    {
        if ($this->selectedId) {
            BroadcastHelper::safeBroadcast(new UserTyping(
                $this->selectedId,
                auth()->id(),
                'admin',
                auth()->user()->name,
                false
            ));
        }
    }

    public function getListeners(): array
    {
        $cid = $this->selectedId;

        if (!$cid) {
            return [];
        }

        // Always return Echo listeners - they will be handled gracefully if Echo is not available
        return [
            "echo-private:conversation.{$cid},MessageSent" => 'messageReceived',
            "echo-private:conversation.{$cid},UserTyping"  => 'userTypingReceived',
        ];
    }

    public function render()
    {
        return view('livewire.admin.pages.chat.admin-chat-app', [
            'conversations' => $this->conversations,
        ]);
    }
}
