<?php

namespace App\Livewire\User\Pages\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UserChatPage extends Component
{
    use WithFileUploads;

    public ?Conversation $conversation = null;
    public string $messageText         = '';
    public Collection $chatMessages;
    public bool $adminIsTyping = false;

    /** pagination state */
    public int $perPage        = 50;
    public ?string $cursor     = null;
    public ?string $nextCursor = null;

    /** new: file uploads */
    public array $uploads        = []; // array of Livewire\Features\SupportFileUploads\TemporaryUploadedFile
    public array $newUploads     = [];  // staging: last picked/dropped files
    public bool $showUploadModal = false;
    public bool $groupItems      = true;
    public bool $compressImages  = false;

    protected function rules(): array
    {
        return [
            'messageText'    => ['nullable', 'string', 'max:5000'],
            'uploads'        => ['array', 'max:5'], // allow up to 5 files per message
            'uploads.*'      => ['file', 'max:20480', 'mimes:jpg,jpeg,png,webp,gif,pdf,txt,zip,doc,docx'],

            'newUploads'     => ['sometimes', 'array'],
            'newUploads.*'   => ['sometimes', 'file', 'max:20480', 'mimes:jpg,jpeg,png,webp,gif,pdf,txt,zip,doc,docx'],

            'groupItems'     => ['boolean'],
            'compressImages' => ['boolean'],
        ];
    }

    /** Auto-open modal right after files are chosen */
    public function updatedUploads(): void
    {
        if (count($this->uploads ?? []) > 0) {
            $this->showUploadModal = true;
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
        $this->showUploadModal = true;    // keep/bring modal up
    }

    /** Cancel: discard selected files & close */
    public function cancelUploads(): void
    {
        $this->uploads         = [];
        $this->reset('newUploads');
        $this->showUploadModal = false;
    }

    /** “Send” from modal: reuse your send() then close */
    public function confirmSendFromModal(): void
    {
        try {
            $this->send();
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            Log::error($e->getMessage());
        }

        $this->showUploadModal = false;
    }

    public function removeUploadByName(string $filename): void
    {
        $this->uploads = collect($this->uploads)
            ->reject(fn ($f) => method_exists($f, 'getFilename') && $f->getFilename() === $filename)
            ->values()
            ->all();
    }

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

    /** cursor-paginated */
    public function loadMessages(): void
    {
        if ( ! $this->conversation) {
            return;
        }

        $page = Message::query()
            ->select(['id', 'conversation_id', 'sender_id', 'sender_type', 'body', 'is_read', 'created_at'])
            ->where('conversation_id', $this->conversation->id)
            ->orderByDesc('id')
            ->cursorPaginate($this->perPage, ['*'], 'cursor', $this->cursor);

        $this->nextCursor   = optional($page->nextCursor())?->encode();
        $this->chatMessages = collect($page->items())->reverse()->values();
    }

    public function loadOlder(): void
    {
        if ($this->nextCursor) {
            $this->cursor = $this->nextCursor;
            $this->loadMessages();
            $this->dispatch('older-loaded');
        }
    }

    /** remove one selected file before sending */
    public function removeUpload(int $index): void
    {
        if (isset($this->uploads[$index])) {
            unset($this->uploads[$index]);
            $this->uploads = array_values($this->uploads);
        }
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function send(): void
    {
        $this->validate();

        // disallow empty message if no files selected
        if (trim($this->messageText) === '' && count($this->uploads) === 0) {
            return;
        }

        if ( ! $this->conversation) {
            $this->ensureConversationExists();
        }

        // ========== CASE 1: Group items into one message ==========
        if ($this->groupItems) {
            $msg = Message::create([
                'conversation_id' => $this->conversation->id,
                'sender_id'       => auth()->id(),
                'sender_type'     => 'user',
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
        }

        // ========== CASE 2: Separate messages ==========
        else {
            $lastMessageId = null;

            // 1. Send text first (if any)
            if (trim($this->messageText) !== '') {
                $textMsg = Message::create([
                    'conversation_id' => $this->conversation->id,
                    'sender_id'       => auth()->id(),
                    'sender_type'     => 'user',
                    'body'            => $this->messageText,
                    'is_read'         => false,
                ]);
                $lastMessageId = $textMsg->id;
            }

            // 2. Send each file as its own message
            foreach ($this->uploads as $file) {
                $fileMsg = Message::create([
                    'conversation_id' => $this->conversation->id,
                    'sender_id'       => auth()->id(),
                    'sender_type'     => 'user',
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
            }
        }

        // Update conversation pointers
        if ($lastMessageId) {
            $this->conversation?->update([
                'last_message_id' => $lastMessageId,
                'last_message_at' => now(),
            ]);
        }

        // reset UI and reload latest page
        $this->messageText = '';
        $this->uploads     = [];

        $this->cursor = null;
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
        $this->cursor = null;
        $this->loadMessages();
        $this->dispatch('message-received');
    }

    public function render()
    {
        return view('livewire.user.pages.chat.user-chat-page')
            ->layout('components.layouts.user_panel', ['external_class' => 'p-0 h-full overflow-hidden']);
    }
}
