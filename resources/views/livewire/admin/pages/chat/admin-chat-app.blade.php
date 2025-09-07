{{-- resources/views/livewire/admin/chats/index.blade.php --}}
@php
    $auth = auth()->user();
    $active = $this->activeConversation ?? null;
@endphp

    <!-- Chat Frame (fits viewport under top navbar, assumed 64px height) -->
<div class="h-[calc(100dvh-64px)] w-full max-w-full overflow-x-hidden overflow-y-hidden
            bg-white rounded-none lg:rounded-3xl shadow-2xl
            grid [grid-template-columns:clamp(220px,22vw,260px)_minmax(0,1fr)_clamp(240px,24vw,300px)]">

    <!-- Left Sidebar -->
    <aside class="bg-neutral-100 border-r border-neutral-200 flex flex-col h-full overflow-hidden min-w-0">
        <!-- Current user / header -->
        <div class="flex items-center gap-3 p-5 shrink-0">
            <img class="h-12 w-12 rounded-full object-cover"
                 src="{{ $auth?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
            <div class="flex-1 min-w-0">
                <div class="font-semibold leading-tight truncate">{{ $auth->name }}</div>
                <div class="text-xs text-neutral-500 truncate">{{ $auth->email }}</div>
            </div>
        </div>

        <!-- Search -->
        <div class="px-5 pb-3 shrink-0">
            <label class="relative block">
        <span class="absolute inset-y-0 left-3 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
          </svg>
        </span>
                <input class="w-full rounded-full border border-neutral-200 bg-white pl-9 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Search Here..." />
            </label>
        </div>

        <!-- Conversations (scrollable) -->
        <nav class="flex-1 overflow-y-auto pb-5 min-h-0">
            @foreach($this->conversations as $conv)
                <button
                    wire:click="open({{ $conv->id }})"
                    wire:key="conv-{{ $conv->id }}"
                    class="w-full text-left px-5 py-3 flex items-center gap-3 hover:bg-white
                 {{ $selectedId === $conv->id ? 'bg-white' : '' }}">
                    <img class="h-10 w-10 rounded-full"
                         src="{{ $conv?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-medium truncate">{{ $conv?->user?->name }}</span>
                            <span class="text-[11px] text-neutral-400">
                {{ optional($conv?->lastMessage?->created_at)->format('H:i') }}
              </span>
                        </div>
                        <div class="text-sm text-neutral-500 truncate">
                            {{ $conv?->lastMessage?->body }}
                        </div>
                    </div>
                    @if(($conv->unread_count ?? 0) > 0)
                        <span class="ml-2 inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-blue-500 text-white text-[11px] px-1">
              {{ $conv->unread_count }}
            </span>
                    @endif
                </button>
            @endforeach
        </nav>
    </aside>

    <!-- Chat Column (middle) -->
    <section class="flex flex-col h-full overflow-hidden min-w-0">
        <!-- Chat Header -->
        <header class="flex items-center gap-3 px-6 py-4 border-b shrink-0">
            @if($active)
                <img class="h-10 w-10 rounded-full"
                     src="{{ $active?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                <div class="flex-1 min-w-0">
                    <div class="font-semibold truncate">{{ $active?->user?->name }}</div>
                    <div class="text-xs text-neutral-500 truncate">{{ $active?->user?->email }}</div>
                </div>
            @else
                <div class="flex-1 text-neutral-400">Select a conversation</div>
            @endif
        </header>

        <!-- Messages (scrollable) -->
        <div id="messages-box" class="flex-1 overflow-y-auto bg-white px-6 py-6 space-y-4 min-h-0">
            @if(!$active)
                <div class="text-center text-neutral-400 mt-20">Choose a conversation from the left.</div>
            @else
                @foreach($this->messages as $m)
                    @php $isMine = $m->sender_id === $auth->id; @endphp

                    <div class="flex items-start gap-3 {{ $isMine ? 'justify-end' : '' }}">
                        @unless($isMine)
                            <img class="h-8 w-8 rounded-full"
                                 src="{{ $active?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                        @endunless

                        <div class="max-w-[65%] rounded-2xl p-3
                        {{ $isMine ? 'bg-blue-500 text-white' : 'bg-blue-50 text-neutral-800' }}">
                            <p class="whitespace-pre-line break-words">{{ $m->body }}</p>
                            <div class="mt-1 text-[11px] opacity-70 text-right">
                                {{ $m->created_at->format('H:i') }}
                            </div>
                        </div>

                        @if($isMine)
                            <img class="h-8 w-8 rounded-full"
                                 src="{{ $auth?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Composer -->
        <footer class="border-t bg-neutral-50 px-4 py-3 shrink-0">
            <div class="flex items-center gap-3">
                <input
                    @disabled(!$active)
                    wire:model.defer="messageText"
                    wire:keydown.enter="send"
                    class="flex-1 rounded-full border border-neutral-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="{{ $active ? 'Write Something...' : 'Select a conversation first' }}" />
                <button class="ml-1 inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-500 text-white shadow-md hover:bg-blue-600"
                        wire:click="send" @disabled(!$active) title="Send">
                    <svg class="h-6 w-6 -mr-0.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2 .01 7z"/>
                    </svg>
                </button>
            </div>
        </footer>
    </section>

    <!-- Right Sidebar -->
    <aside class="bg-neutral-100 border-l border-neutral-200 flex flex-col h-full overflow-hidden min-w-0">
        <div class="p-5 flex-1 overflow-y-auto min-h-0">
            @if($active)
                <div class="flex flex-col items-center text-center">
                    <img class="h-28 w-28 rounded-full object-cover mb-3"
                         src="{{ $active?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                    <div class="font-semibold">{{ $active?->user?->name }}</div>
                    <div class="text-xs text-neutral-500 mb-5">{{ $active?->user?->email }}</div>

                    <div class="grid grid-cols-2 gap-4 w-full">
                        <button class="flex flex-col items-center gap-2 rounded-2xl bg-white p-4 shadow-sm border hover:shadow">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M2 8a2 2 0 012-2h4l2-2h4l2 2h4a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V8z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Chat</span>
                        </button>
                        <button class="flex flex-col items-center gap-2 rounded-2xl bg-white p-4 shadow-sm border hover:shadow">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M15 10.5V6a3 3 0 00-6 0v4.5a3 3 0 106 0z"/><path d="M5 10v4a7 7 0 0014 0v-4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Video Call</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center text-neutral-400 mt-20">No user selected</div>
            @endif
        </div>
    </aside>
</div>
