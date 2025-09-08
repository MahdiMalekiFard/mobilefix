@php
    $auth = auth()->user();
    $active = $this->activeConversation ?? null;
@endphp

<div
    x-data="{ drawer:false }"
    class="h-[calc(100dvh-64px)] w-full overflow-hidden
           bg-white dark:bg-neutral-900
           grid grid-cols-1 lg:[grid-template-columns:clamp(320px,28vw,420px)_minmax(0,1fr)]">

    {{-- ========== LEFT SIDEBAR (Conversations) ========== --}}
    <aside class="hidden lg:flex flex-col min-w-0 h-full overflow-hidden
                border-r border-neutral-200 dark:border-neutral-800
                bg-neutral-100 dark:bg-neutral-900/40">
        {{-- Current user --}}
        <div class="flex items-center gap-3 p-5 shrink-0">
            <img class="h-12 w-12 rounded-full object-cover"
                 src="{{ $auth?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
            <div class="flex-1 min-w-0">
                <div class="font-semibold leading-tight truncate text-neutral-900 dark:text-neutral-100">{{ $auth->name }}</div>
                <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ $auth->email }}</div>
            </div>
        </div>

        {{-- Search --}}
        <div class="px-5 pb-3 shrink-0">
            <label class="relative block">
                <span class="absolute inset-y-0 left-3 flex items-center">
                  <svg class="h-4 w-4 text-neutral-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                  </svg>
                </span>
                <input
                    wire:model.live.debounce.300ms="search"
                    class="w-full rounded-full border border-neutral-200 dark:border-neutral-800
                             bg-white dark:bg-neutral-800
                             text-neutral-800 dark:text-neutral-100
                             placeholder-neutral-400 dark:placeholder-neutral-500
                             pl-9 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/60"
                    placeholder="Search users..."
                />
            </label>
        </div>

        {{-- List (scrollable) --}}
        <nav class="flex-1 overflow-y-auto min-h-0 pb-5">
            @foreach($this->conversations as $conv)
                <button
                    wire:click="open({{ $conv->id }})"
                    wire:key="conv-{{ $conv->id }}"
                    class="w-full text-left px-5 py-3 flex items-center gap-3
                     hover:bg-white dark:hover:bg-neutral-800 hover:cursor-pointer
                    {{ $selectedId === $conv->id ? 'bg-white dark:bg-neutral-800' : '' }}"
                >
                    <img class="h-10 w-10 rounded-full"
                         src="{{ $conv?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-medium truncate text-neutral-900 dark:text-neutral-100">{{ $conv?->user?->name }}</span>
                            <span class="text-[11px] text-neutral-400 dark:text-neutral-500">
                                {{ optional($conv?->lastMessage?->created_at)->format('H:i') }}
                            </span>
                        </div>
                        <div class="text-sm truncate text-neutral-500 dark:text-neutral-400">
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

    {{-- Mobile/Tablet: off-canvas drawer for conversations --}}
    <div
        x-show="drawer"
        x-transition.opacity
        @keydown.escape.window="drawer=false"
        @click.self="drawer=false"
        class="lg:hidden fixed inset-0 z-40 bg-black/40">
        <div
            x-show="drawer"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-x-[-100%] opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-[-100%] opacity-0"
            class="absolute left-0 top-0 h-full w-[85vw] max-w-sm
             bg-neutral-100 dark:bg-neutral-900/95
             border-r border-neutral-200 dark:border-neutral-800
             flex flex-col overflow-hidden">

            {{-- Drawer header --}}
            <div class="flex items-center justify-between gap-3 p-4 border-b border-neutral-200 dark:border-neutral-800">
                <div class="flex items-center gap-3 min-w-0">
                    <img class="h-10 w-10 rounded-full object-cover"
                         src="{{ $auth?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                    <div class="min-w-0">
                        <div class="font-semibold leading-tight truncate text-neutral-900 dark:text-neutral-100">{{ $auth->name }}</div>
                        <div class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ $auth->email }}</div>
                    </div>
                </div>
                <button @click="drawer=false" class="p-2 rounded-full hover:bg-neutral-200/60 dark:hover:bg-neutral-800">
                    <svg class="h-5 w-5 text-neutral-700 dark:text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Search --}}
            <div class="px-4 py-3">
                <label class="relative block">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <svg class="h-4 w-4 text-neutral-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                    </span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        class="w-full rounded-full border border-neutral-200 dark:border-neutral-800
                             bg-white dark:bg-neutral-800
                             text-neutral-800 dark:text-neutral-100
                             placeholder-neutral-400 dark:placeholder-neutral-500
                             pl-9 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/60"
                        placeholder="Search users..."
                    />
                </label>
            </div>

            {{-- Drawer list (scrollable) --}}
            <nav class="flex-1 overflow-y-auto min-h-0 pb-4">
                @foreach($this->conversations as $conv)
                    <button
                        wire:click="open({{ $conv->id }}); $dispatch('close-drawer')"
                        wire:key="mconv-{{ $conv->id }}"
                        @click="drawer=false"
                        class="w-full text-left px-4 py-3 flex items-center gap-3
                            hover:bg-white dark:hover:bg-neutral-800
                            {{ $selectedId === $conv->id ? 'bg-white dark:bg-neutral-800' : '' }}"
                    >
                        <img class="h-10 w-10 rounded-full"
                             src="{{ $conv?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-medium truncate text-neutral-900 dark:text-neutral-100">{{ $conv?->user?->name }}</span>
                                <span class="text-[11px] text-neutral-400 dark:text-neutral-500">
                                    {{ optional($conv?->lastMessage?->created_at)->format('H:i') }}
                                </span>
                            </div>
                            <div class="text-sm truncate text-neutral-500 dark:text-neutral-400">
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
        </div>
    </div>

    {{-- ========== MESSAGES COLUMN ========== --}}
    <section class="flex flex-col min-w-0 h-full overflow-hidden">
        {{-- Header --}}
        <header class="flex items-center gap-3 px-4 lg:px-6 py-3 lg:py-4 border-b border-neutral-200 dark:border-neutral-800 shrink-0">
            <button class="lg:hidden p-2 mr-1 rounded-full hover:bg-neutral-200/60 dark:hover:bg-neutral-800"
                    @click="drawer=true" title="Conversations">
                <svg class="h-5 w-5 text-neutral-700 dark:text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/>
                </svg>
            </button>

            @if($active)
                <img class="h-10 w-10 rounded-full"
                     src="{{ $active?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                <div class="flex-1 min-w-0">
                    <div class="font-semibold truncate text-neutral-900 dark:text-neutral-100">{{ $active?->user?->name }}</div>
                    <div class="text-xs truncate text-neutral-500 dark:text-neutral-400">{{ $active?->user?->email }}</div>
                </div>
            @else
                <div class="flex-1 text-neutral-400 dark:text-neutral-500">Select a conversation</div>
            @endif
        </header>

        <!-- Messages (scrollable, bottom-aligned when short) -->
        <div id="messages-box"
             x-data="{ atBottom: true }"
             x-ref="box"
             @scroll-bottom.window="$nextTick(() => {
                 $refs.box.scrollTo({ top: $refs.box.scrollHeight, behavior: 'smooth' });
             })"
             @scroll.passive="atBottom = ($el.scrollTop + $el.clientHeight) >= ($el.scrollHeight - 50)"
             class="relative flex-1 overflow-y-auto overflow-x-hidden px-4 lg:px-6 py-4 lg:py-6 min-h-0 bg-white dark:bg-neutral-900">

            @if(!$active)
                <div class="text-center text-neutral-400 dark:text-neutral-500 mt-20">
                    Choose a conversation from the left.
                </div>
            @else
                <div class="min-h-full flex flex-col justify-end">
                    <div class="space-y-4">

                        {{-- Load older appears when there is a next cursor --}}
                        @if($this->nextCursor)
                            <div class="flex justify-center">
                                <button wire:click="loadOlder"
                                        class="text-xs px-3 py-1 rounded-full border border-neutral-300 dark:border-neutral-700
                                               hover:bg-neutral-100 dark:hover:bg-neutral-800">
                                    Load older messages
                                </button>
                            </div>
                        @endif

                        @foreach($this->messages as $m)
                            @php $isMine = $m->sender_id === auth()->id(); @endphp
                            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}" wire:key="m-{{ $m->id }}">
                                <div class="relative max-w-[85%] lg:max-w-[70%]">
                                    <div class="{{ $isMine
                                        ? 'bg-blue-500 text-white rounded-2xl rounded-br-none'
                                        : 'bg-neutral-100 text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100 rounded-2xl rounded-bl-none'
                                        }} px-4 py-3 shadow-sm break-words">
                                        <p class="whitespace-pre-line">{{ $m->body }}</p>
                                        <div class="mt-1 text-[11px] opacity-70 text-right {{ $isMine ? 'text-white/80' : 'text-neutral-500 dark:text-neutral-400' }}">
                                            {{ $m->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="messages-end" wire:key="messages-end"></div>
                    </div>
                </div>
            @endif

            {{-- Sticky scroll-to-bottom button inside the messages area --}}
            <div class="sticky bottom-4 flex justify-center pointer-events-none">
                <button
                    x-cloak
                    x-show="!atBottom"
                    x-transition.opacity
                    @click="$refs.box.scrollTo({ top: $refs.box.scrollHeight, behavior: 'smooth' })"
                    class="pointer-events-auto z-10 h-10 w-10 flex items-center justify-center
                           rounded-full bg-neutral-400/50 hover:bg-neutral-500/70 hover:cursor-pointer
                           text-white shadow-md"
                    title="Scroll to bottom">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path d="M12 16.5l-6-6 1.4-1.4L12 13.7l4.6-4.6 1.4 1.4-6 6z"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Composer --}}
        <footer class="border-t border-neutral-200 dark:border-neutral-800
                   bg-neutral-50 dark:bg-neutral-900/40 px-3 lg:px-4 py-3 shrink-0">
            <div class="flex items-center gap-2 lg:gap-3">
                <input
                    @disabled(!$active)
                    wire:model.defer="messageText"
                    wire:keydown.enter="send"
                    class="flex-1 rounded-full border
                           border-neutral-200 dark:border-neutral-800
                           bg-white dark:bg-neutral-800
                           text-neutral-800 dark:text-neutral-100
                           placeholder-neutral-400 dark:placeholder-neutral-500
                           px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/60"
                    placeholder="{{ $active ? 'Write Something...' : 'Select a conversation first' }}" />
                <button
                    class="inline-flex items-center justify-center h-11 w-11 lg:h-12 lg:w-12 rounded-full
                           bg-blue-500 hover:bg-blue-600 text-white dark:bg-blue-600 dark:hover:bg-blue-700
                           shadow-md disabled:opacity-40 hover:cursor-pointer"
                    wire:click="send" @disabled(!$active) title="Send">
                    <svg class="h-5 w-5 lg:h-6 lg:w-6 -mr-0.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2 .01 7z"/>
                    </svg>
                </button>
            </div>
        </footer>
    </section>
</div>
