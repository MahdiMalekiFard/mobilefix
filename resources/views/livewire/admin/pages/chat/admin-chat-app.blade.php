@php
    $auth = auth()->user();
    $active = $this->activeConversation ?? null;
@endphp

<div
    x-data="{ 
        ...adminEcho(@entangle('selectedId').live), 
        drawer: false,
        globalDragOver: false,
        handleGlobalDragOver(event) {
            // Only show global overlay if a conversation is selected and not over footer
            if (this.sid && !event.target.closest('footer')) {
                event.preventDefault();
                this.globalDragOver = true;
            }
        },
        handleGlobalDragLeave(event) {
            if (!event.currentTarget.contains(event.relatedTarget)) {
                this.globalDragOver = false;
            }
        },
        handleGlobalDrop(event) {
            this.globalDragOver = false;
            const files = event.dataTransfer?.files;
            if (files?.length && this.sid) {
                // Check if the drop happened on the footer - if so, let it handle it
                const footer = event.target.closest('footer');
                if (!footer) {
                    event.preventDefault();
                    // Only dispatch if not dropped on footer
                    $dispatch('chat-files-dropped', { files });
                }
            }
        }
    }"
    x-init="boot()"
    @dragover="handleGlobalDragOver($event)"
    @dragleave="handleGlobalDragLeave($event)"
    @drop="handleGlobalDrop($event)"
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
                    {{ $selectedId === $conv->id ? 'bg-white dark:bg-neutral-800' : '' }}">
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

    {{-- Mobile Drawer --}}
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

            {{-- Drawer search --}}
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

            {{-- Drawer list --}}
            <nav class="flex-1 overflow-y-auto min-h-0 pb-4">
                @foreach($this->conversations as $conv)
                    <button
                        wire:click="open({{ $conv->id }}); $dispatch('close-drawer')"
                        wire:key="mconv-{{ $conv->id }}"
                        @click="drawer=false"
                        class="w-full text-left px-4 py-3 flex items-center gap-3
                            hover:bg-white dark:hover:bg-neutral-800
                            {{ $selectedId === $conv->id ? 'bg-white dark:bg-neutral-800' : '' }}">
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

        <!-- Messages (also a drop target) -->
        <div id="messages-box"
             x-data="{ 
                atBottom: true,
                dragOver: false,
                handleDragOver(event) {
                    event.preventDefault();
                    this.dragOver = true;
                },
                handleDragLeave(event) {
                    event.preventDefault();
                    // Only set dragOver to false if we're leaving the container itself
                    if (!event.currentTarget.contains(event.relatedTarget)) {
                        this.dragOver = false;
                    }
                },
                handleDrop(event) {
                    event.preventDefault();
                    this.dragOver = false;
                    const files = event.dataTransfer?.files;
                    if (files?.length) $dispatch('chat-files-dropped', { files });
                }
             }"
             x-ref="box"
             @scroll-bottom.window="$nextTick(() => { $refs.box.scrollTo({ top: $refs.box.scrollHeight, behavior: 'smooth' }); })"
             @scroll.passive="atBottom = ($el.scrollTop + $el.clientHeight) >= ($el.scrollHeight - 50)"
             @dragover="handleDragOver($event)"
             @dragleave="handleDragLeave($event)"
             @drop="handleDrop($event)"
             class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden px-4 @lg:px-6 py-4 @lg:py-6
                        bg-gradient-to-b from-neutral-50/50 to-white dark:from-neutral-900 dark:to-neutral-900
                        transition-colors duration-200 relative"
             :class="dragOver ? 'bg-blue-50/50 dark:bg-blue-900/20' : ''">

            @if(!$active)
                <div class="text-center text-neutral-400 dark:text-neutral-500 mt-20">
                    Choose a conversation from the left.
                </div>
            @else
                <div class="min-h-full flex flex-col justify-end">
                    <div class="space-y-4">

                        {{-- Load older --}}
                        @if($this->nextCursor)
                            <div class="flex justify-center">
                                <button
                                    wire:click="loadOlder"
                                    x-on:click="window.__ac_prevH = $refs.box?.scrollHeight || 0;"
                                    class="text-xs px-3 py-1 rounded-full border border-neutral-300 dark:border-neutral-700
                                           hover:bg-neutral-100 dark:hover:bg-neutral-800">
                                    Load older messages
                                </button>
                            </div>
                        @endif

                        @foreach($this->messages as $m)
                            @php
                                $isMine   = $m->sender_id === auth()->id();
                                $media    = $m->getMedia('attachments');
                                $images   = $media->filter(fn($media_item) => str_starts_with($media_item->mime_type, 'image/'));
                                $files    = $media->reject(fn($media_item) => str_starts_with($media_item->mime_type, 'image/'));
                                $hasBody  = trim($m->body) !== '';
                            @endphp
                            <div class="flex {{ $isMine ? 'justify-start' : 'justify-end' }} mb-10" wire:key="m-{{ $m->id }}">
                                {{-- ADMIN (blue, left) --}}
                                @if($isMine)
                                    <div class="flex flex-col gap-2 max-w-[85%] @lg:max-w-[70%] items-start">

                                        {{-- TEXT --}}
                                        @if($hasBody)
                                            <div class="bg-blue-50 ring-1 ring-blue-200 text-blue-900 dark:bg-blue-600/20 dark:ring-blue-400/40 dark:text-blue-100 rounded-2xl rounded-bl-none px-4 py-3 text-sm font-medium">
                                                <div class="whitespace-pre-line">{{ $m->body }}</div>
                                            </div>
                                        @endif

                                        {{-- IMAGES (align left) --}}
                                        @if($images->isNotEmpty())
                                            <div class="{{ $images->count() > 1 ? 'grid grid-cols-2 gap-2' : 'flex' }} items-start">
                                                @foreach($images as $im)
                                                    <a href="{{ $im->getFullUrl() }}" target="_blank" class="block">
                                                        <img src="{{ $im->getFullUrl() }}"
                                                             alt="{{ $im->file_name }}"
                                                             class="w-[260px] h-[160px] object-cover rounded-2xl border border-neutral-200 dark:border-neutral-700" />
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- FILES (align left, blue-tinted card styles kept) --}}
                                        @if($files->isNotEmpty())
                                            @foreach($files as $md)
                                                @php
                                                    $bytes = $md->size;
                                                    $sizeLabel = $bytes < 1024 ? ($bytes.' B') : ($bytes < 1048576 ? number_format($bytes/1024,0).' KB' : number_format($bytes/1048576,2).' MB');
                                                    $full = $md->file_name;
                                                    $ext  = pathinfo($full, PATHINFO_EXTENSION);
                                                    $base = pathinfo($full, PATHINFO_FILENAME);
                                                    $nameShort = (mb_strlen($base) > 18) ? mb_substr($base,0,12).'…'.mb_substr($base,-4).($ext?'.'.$ext:'') : $full;

                                                    // user-side (left) card palette
                                                    $cardBg  = 'bg-blue-50 ring-1 ring-blue-200 text-blue-900 dark:bg-blue-600/20 dark:ring-blue-400/40 dark:text-blue-100';
                                                    $iconBg  = 'bg-blue-100 dark:bg-blue-500/30';
                                                    $iconClr = 'text-blue-700 dark:text-blue-300';
                                                    $metaClr = 'text-blue-700/70 dark:text-blue-300/80';
                                                    $hoverBg = 'hover:bg-blue-100/70 dark:hover:bg-blue-600/40';
                                                @endphp

                                                <div
                                                    x-data="fileCard({
                                                                srcUrl:   @js(parse_url($md->getFullUrl(), PHP_URL_PATH), JSON_THROW_ON_ERROR),
                                                                filename: @js($md->file_name, JSON_THROW_ON_ERROR),
                                                            })"
                                                    class="flex flex-col items-start"
                                                >
                                                    <div @click="openOrSave" @contextmenu.prevent="onContextMenu"
                                                         class="flex items-center gap-3 px-3 py-2 rounded-2xl shadow-sm cursor-pointer transition {{ $cardBg }} {{ $hoverBg }}">
                                                        <div class="shrink-0 h-10 w-10 rounded-full flex items-center justify-center {{ $iconBg }}">
                                                            <svg class="h-5 w-5 {{ $iconClr }}" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M7 2h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm7 2v4h4"/>
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <div class="text-sm font-medium truncate max-w-[220px]" title="{{ $md->file_name }}">{{ $nameShort }}</div>
                                                            <div class="text-[11px] {{ $metaClr }}">{{ $sizeLabel }}</div>
                                                        </div>
                                                    </div>

                                                    {{-- optional save modal kept as-is --}}
                                                    <div x-show="showSaveModal" x-cloak x-transition.opacity class="fixed inset-0 z-[70] flex items-center justify-center">
                                                        <div class="absolute inset-0 bg-black/40" @click="showSaveModal=false"></div>
                                                        <div class="relative w-[90vw] max-w-sm rounded-2xl bg-white dark:bg-neutral-900 shadow-2xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
                                                            <div class="px-4 py-3 border-b border-neutral-200 dark:border-neutral-800 font-semibold">Save File</div>
                                                            <div class="p-4 text-sm text-neutral-700 dark:text-neutral-300">Do you want to save <span class="font-medium" x-text="filename"></span>?</div>
                                                            <div class="flex items-center justify-end gap-2 px-4 py-3 border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/60">
                                                                <button class="px-3 py-2 text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:cursor-pointer" @click="showSaveModal=false">Cancel</button>
                                                                <button class="px-3 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow hover:cursor-pointer" @click="confirmSave">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="text-[11px] opacity-70 text-neutral-500 dark:text-neutral-400 text-left">{{ $m->created_at->format('g:i A') }}</div>
                                    </div>
                                @else
                                    {{-- USER (white, right) with avatar on the right for all blocks --}}
                                    <div class="flex items-end gap-2 max-w-[85%] @lg:max-w-[70%]">

                                        {{-- CONTENT COLUMN (bubble(s) + images + files + time) --}}
                                        <div class="flex flex-col items-end gap-2">
                                            {{-- TEXT --}}
                                            @if($hasBody)
                                                <div class="bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100
                                                    rounded-2xl rounded-br-none border border-neutral-200 dark:border-neutral-700 px-4 py-3">
                                                    <div class="whitespace-pre-line">{{ $m->body }}</div>
                                                </div>
                                            @endif

                                            {{-- IMAGES (align right; keep grid) --}}
                                            @if($images->isNotEmpty())
                                                <div class="{{ $images->count() > 1 ? 'grid grid-cols-2 gap-2' : 'flex' }} justify-end">
                                                    @foreach($images as $im)
                                                        <a href="{{ $im->getFullUrl() }}" target="_blank" class="block">
                                                            <img src="{{ $im->getFullUrl() }}"
                                                                 alt="{{ $im->file_name }}"
                                                                 class="w-[260px] h-[160px] object-cover rounded-2xl border border-neutral-200 dark:border-neutral-700" />
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- FILES (align right; neutral card palette) --}}
                                            @if($files->isNotEmpty())
                                                <div class="flex flex-col items-end gap-2">
                                                    @foreach($files as $md)
                                                        @php
                                                            $bytes = $md->size;
                                                            $sizeLabel = $bytes < 1024 ? ($bytes.' B') : ($bytes < 1048576 ? number_format($bytes/1024,0).' KB' : number_format($bytes/1048576,2).' MB');
                                                            $full = $md->file_name;
                                                            $ext  = pathinfo($full, PATHINFO_EXTENSION);
                                                            $base = pathinfo($full, PATHINFO_FILENAME);
                                                            $nameShort = (mb_strlen($base) > 18) ? mb_substr($base,0,12).'…'.mb_substr($base,-4).($ext?'.'.$ext:'') : $full;

                                                            // admin-side neutral palette
                                                            $cardBg  = 'bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100';
                                                            $iconBg  = 'bg-neutral-100 dark:bg-neutral-700';
                                                            $iconClr = 'text-neutral-700 dark:text-neutral-300';
                                                            $metaClr = 'text-neutral-500 dark:text-neutral-400';
                                                            $hoverBg = 'hover:bg-neutral-50 dark:hover:bg-neutral-700/60';
                                                        @endphp

                                                        <div
                                                            x-data="fileCard({
                                                                srcUrl:   @js(parse_url($md->getFullUrl(), PHP_URL_PATH), JSON_THROW_ON_ERROR),
                                                                filename: @js($md->file_name, JSON_THROW_ON_ERROR),
                                                            })"
                                                            class="flex flex-col items-end"
                                                        >
                                                            <div
                                                                @click="openOrSave"
                                                                @contextmenu.prevent="onContextMenu"
                                                                class="flex items-center gap-3 px-3 py-2 rounded-2xl shadow-sm cursor-pointer transition {{ $cardBg }} {{ $hoverBg }}"
                                                            >
                                                                <div class="shrink-0 h-10 w-10 rounded-full flex items-center justify-center {{ $iconBg }}">
                                                                    <svg class="h-5 w-5 {{ $iconClr }}" viewBox="0 0 24 24" fill="currentColor">
                                                                        <path d="M7 2h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm7 2v4h4"/>
                                                                    </svg>
                                                                </div>

                                                                <div class="min-w-0">
                                                                    <div class="text-sm font-medium truncate max-w-[220px]" title="{{ $md->file_name }}">
                                                                        {{ $nameShort }}
                                                                    </div>
                                                                    <div class="text-[11px] {{ $metaClr }}">
                                                                        {{ $sizeLabel }}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Save Modal (only shows for previewable files) -->
                                                            <div
                                                                x-show="showSaveModal"
                                                                x-cloak
                                                                x-transition.opacity
                                                                class="fixed inset-0 z-[70] flex items-center justify-center"
                                                                aria-modal="true" role="dialog"
                                                            >
                                                                <div class="absolute inset-0 bg-black/40" @click="showSaveModal=false"></div>
                                                                <div class="relative w-[90vw] max-w-sm rounded-2xl bg-white dark:bg-neutral-900 shadow-2xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
                                                                    <div class="px-4 py-3 border-b border-neutral-200 dark:border-neutral-800 font-semibold">
                                                                        Save File
                                                                    </div>
                                                                    <div class="p-4 text-sm text-neutral-700 dark:text-neutral-300">
                                                                        Do you want to save <span class="font-medium" x-text="filename"></span>?
                                                                    </div>
                                                                    <div class="flex items-center justify-end gap-2 px-4 py-3 border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/60">
                                                                        <button class="px-3 py-2 text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:cursor-pointer"
                                                                                @click="showSaveModal=false">Cancel
                                                                        </button>
                                                                        <button class="px-3 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow hover:cursor-pointer"
                                                                                @click="confirmSave">Save
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- TIME (under the whole message) --}}
                                            <div class="text-[11px] opacity-70 text-neutral-500 dark:text-neutral-400 text-right mt-1">
                                                {{ $m->created_at->format('g:i A') }}
                                            </div>
                                        </div>

                                        {{-- AVATAR (only once for the whole message) --}}
                                        <div class="ml-2 flex-shrink-0 self-end">
                                            <img class="h-8 w-8 rounded-full flex items-center justify-center shadow-md"
                                                 src="{{ $active?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                                        </div>

                                    </div>
                                @endif
                            </div>
                        @endforeach

                        {{-- User typing indicator --}}
                        @if($userIsTyping && $active)
                            <div class="flex justify-start mb-4">
                                <div class="flex items-end gap-2 max-w-[85%] @lg:max-w-[70%]">
                                    <div class="flex flex-col items-end gap-2">
                                        <div class="bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100
                                                    rounded-2xl rounded-bl-none border border-neutral-200 dark:border-neutral-700 px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="flex gap-1">
                                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce"></div>
                                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 120ms;"></div>
                                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 240ms;"></div>
                                                </div>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $active?->user?->name }} is typing...</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- AVATAR --}}
                                    <div class="ml-2 flex-shrink-0 self-end">
                                        <img class="h-8 w-8 rounded-full flex items-center justify-center shadow-md"
                                             src="{{ $active?->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div id="messages-end" wire:key="messages-end"></div>
                    </div>
                </div>
            @endif

            {{-- Sticky scroll-to-bottom button --}}
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

        {{-- Composer (input + attachments) WITH DRAG & DROP --}}
        <footer
            x-data="{
                enabled: {{ $active ? 'true' : 'false' }},
                dragOver: false,
                accept: [
                    // Images
                    'image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif', 'image/bmp', 'image/svg+xml',
                    // Documents
                    'application/pdf', 'text/plain', 'text/csv',
                    // Office Documents
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    // Archives
                    'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
                    // Audio
                    'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3',
                    // Video
                    'video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/webm',
                    // Other common types
                    'application/json', 'application/xml', 'text/xml'
                ],
                maxFiles: 5,
                
                handleDragOver(event) {
                    event.preventDefault();
                    if (this.enabled) this.dragOver = true;
                },
                
                handleDragLeave(event) {
                    event.preventDefault();
                    // Only set dragOver to false if we're leaving the container itself
                    if (!event.currentTarget.contains(event.relatedTarget)) {
                        this.dragOver = false;
                    }
                },
                
                handleDrop(event) {
                    event.preventDefault();
                    this.dragOver = false;
                    if (this.enabled && event.dataTransfer?.files?.length) {
                        this.addFiles(event.dataTransfer.files);
                    }
                },
                
                addFiles(files) {
                    const filtered = Array.from(files).filter(f => {
                        if (!this.accept.length) return true;
                        
                        // Check exact MIME type match
                        if (this.accept.includes(f.type)) return true;
                        
                        // Check for image types (more permissive)
                        if (f.type.startsWith('image/')) return true;
                        
                        // Check for audio types
                        if (f.type.startsWith('audio/')) return true;
                        
                        // Check for video types
                        if (f.type.startsWith('video/')) return true;
                        
                        // Check for text types
                        if (f.type.startsWith('text/')) return true;
                        
                        // Check for application types that might be documents
                        if (f.type.startsWith('application/') && 
                            (f.type.includes('pdf') || 
                             f.type.includes('word') || 
                             f.type.includes('excel') || 
                             f.type.includes('powerpoint') || 
                             f.type.includes('zip') || 
                             f.type.includes('rar') ||
                             f.type.includes('json') ||
                             f.type.includes('xml'))) {
                            return true;
                        }
                        
                        // If no MIME type or unknown type, check file extension
                        const fileName = f.name.toLowerCase();
                        const allowedExtensions = [
                            '.jpg', '.jpeg', '.png', '.gif', '.webp', '.bmp', '.svg',
                            '.pdf', '.txt', '.csv',
                            '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx',
                            '.zip', '.rar', '.7z',
                            '.mp3', '.wav', '.ogg',
                            '.mp4', '.avi', '.mov', '.wmv', '.webm',
                            '.json', '.xml'
                        ];
                        
                        return allowedExtensions.some(ext => fileName.endsWith(ext));
                    });
                    
                    // Check if we should replace existing files or add to them
                    const shouldReplace = this.$refs.file.files?.length > 0;
                    
                    if (shouldReplace) {
                        // Replace existing files
                        const dt = new DataTransfer();
                        filtered.slice(0, this.maxFiles).forEach(f => dt.items.add(f));
                        this.$refs.file.files = dt.files;
                    } else {
                        // Add to existing files (if any)
                        const currentFileCount = this.$refs.file.files?.length || 0;
                        const availableSlots = this.maxFiles - currentFileCount;
                        
                        if (availableSlots <= 0) {
                            this.showFileLimitMessage();
                            return;
                        }
                        
                        const filesToAdd = filtered.slice(0, availableSlots);
                        
                        if (filesToAdd.length < filtered.length) {
                            this.showFileLimitMessage();
                        }
                        
                        const dt = new DataTransfer();
                        if (this.$refs.file.files?.length) {
                            Array.from(this.$refs.file.files).forEach(f => dt.items.add(f));
                        }
                        
                        filesToAdd.forEach(f => dt.items.add(f));
                        this.$refs.file.files = dt.files;
                    }
                    
                    this.$refs.file.dispatchEvent(new Event('change', { bubbles: true }));
                },
                
                showFileLimitMessage() {
                    // You could implement a toast notification here
                    console.log(`Maximum ${this.maxFiles} files allowed`);
                }
            }"
            @dragover="handleDragOver($event)"
            @dragleave="handleDragLeave($event)"
            @drop="handleDrop($event)"
            @chat-files-dropped.window="if (enabled && $event.detail?.files) addFiles($event.detail.files)"
            @focus-composer.window="$nextTick(() => {
                const i = $refs.composer;
                if (!i) return;
                i.focus();
                // place caret at end (or use 0,0 if you prefer start)
                try { i.setSelectionRange(i.value.length, i.value.length); } catch(_) {}
                // ensure it's visible if the page scrolled
                i.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            })"
            class="border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/40 px-3 lg:px-4 py-3 shrink-0 transition-all duration-200"
            :class="enabled && dragOver ? 'ring-2 ring-blue-400/40 bg-blue-50/50 dark:bg-blue-900/20' : ''"
        >

            {{-- selected files preview --}}
            @if(count($uploads ?? []))
                <div class="mb-3 flex flex-wrap gap-2">
                    @foreach($uploads as $file)
                        @php
                            $isImg = str_starts_with($file->getMimeType(), 'image/');
                            $tempName = $file->getFilename();
                        @endphp
                        <div class="relative group" wire:key="upload-{{ $tempName }}">
                            @if($isImg)
                                <img src="{{ $file->temporaryUrl() }}"
                                     class="h-16 w-16 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700"/>
                            @else
                                <div class="h-16 w-44 flex items-center gap-2 px-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white/70 dark:bg-neutral-800/70">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14.59 2.59L21 9l-7 7-6.41-6.41L14.59 2.59zM3 13l4 4H3v-4z"/>
                                    </svg>
                                    <span class="truncate text-xs">{{ $file->getClientOriginalName() }}</span>
                                </div>
                            @endif
                            <button type="button"
                                    class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 text-white text-xs hidden group-hover:flex items-center justify-center"
                                    wire:click="removeUploadByName(@js($tempName))"
                                    title="Remove">×
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- upload progress --}}
            <div x-data="{p:0, up:false}"
                 x-on:livewire-upload-start="up=true"
                 x-on:livewire-upload-finish="up=false; p=0"
                 x-on:livewire-upload-error="up=false; p=0"
                 x-on:livewire-upload-progress="p=$event.detail.progress">
                <div x-show="up" class="w-full h-1 bg-neutral-200 dark:bg-neutral-800 rounded overflow-hidden">
                    <div class="h-1 bg-blue-500" :style="`width:${p}%;`"></div>
                </div>
            </div>

            <!-- Sending indicator -->
            <div wire:loading wire:target="send" class="mb-2 flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Sending message...</span>
            </div>

            <div class="mt-2 flex items-center gap-2 lg:gap-3">
                {{-- hidden file input --}}
                <input
                    id="chat-file-input"
                    type="file"
                    multiple
                    class="hidden"
                    x-ref="file"
                    wire:model="newUploads"
                    accept=".jpg,.jpeg,.png,.webp,.gif,.bmp,.svg,.pdf,.txt,.csv,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.mp3,.wav,.ogg,.mp4,.avi,.mov,.wmv,.webm,.json,.xml,image/*,audio/*,video/*"
                />

                {{-- improved attach button --}}
                <button type="button"
                        @click="$refs.file.click()"
                        @disabled(!$active)
                        wire:loading.attr="disabled"
                        wire:target="send"
                        aria-disabled="{{ $active ? 'false' : 'true' }}"
                        class="group relative inline-flex h-11 w-11 lg:h-12 lg:w-12 items-center justify-center rounded-full border transition
                            {{ $active
                                ? 'border-neutral-200 dark:border-neutral-700 bg-white hover:cursor-pointer dark:bg-neutral-800 shadow-sm hover:shadow-md hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed'
                                : 'border-neutral-200 dark:border-neutral-800 bg-neutral-200/60 dark:bg-neutral-800/40 opacity-40 cursor-not-allowed pointer-events-none' }}"
                        title="{{ $active ? 'Attach files' : 'Select a conversation first' }}">
                    <span class="pointer-events-none absolute inset-0 rounded-full ring-0 group-hover:ring-4 ring-blue-500/5 transition"></span>
                    <svg viewBox="0 0 24 24"
                         class="h-5 w-5 lg:h-6 lg:w-6 text-neutral-600 dark:text-neutral-300"
                         fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.44 11.05 12 20.5a6.5 6.5 0 1 1-9.19-9.19l10-10a4.5 4.5 0 1 1 6.36 6.36L8.5 18.5a2.5 2.5 0 1 1-3.54-3.54L15 3"/>
                    </svg>
                </button>

                {{-- text input --}}
                <input
                    @disabled(!$active)
                    wire:loading.attr="disabled"
                    wire:target="send"
                    x-ref="composer"
                    wire:model.defer="messageText"
                    wire:keydown.enter="send"
                    autocomplete="off"
                    x-data="{
                        typingTimer: null,
                        startTyping() {
                            const isActive = $wire.get('selectedId') !== null;
                            const hasText = $el.value && $el.value.trim() !== '';
                            if (isActive && hasText) {
                                $wire.startTyping();
                                clearTimeout(this.typingTimer);
                                this.typingTimer = setTimeout(() => {
                                    $wire.stopTyping();
                                }, 2000);
                            } else if (isActive && !hasText) {
                                // Stop typing if input becomes empty
                                clearTimeout(this.typingTimer);
                                $wire.stopTyping();
                            }
                        }
                    }"
                    @input="startTyping()"
                    @blur="$wire.stopTyping()"
                    @message-sent.window="setTimeout(() => { $el.value = ''; }, 100)"
                    class="flex-1 rounded-full border
                           border-neutral-200 dark:border-neutral-800
                           bg-white dark:bg-neutral-800
                           text-neutral-800 dark:text-neutral-100
                           placeholder-neutral-400 dark:placeholder-neutral-500
                           px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/60
                           disabled:opacity-50 disabled:cursor-not-allowed"
                    placeholder="{{ $active ? 'Write Something...' : 'Select a conversation first' }}"/>

                {{-- send --}}
                <button
                    wire:click="send"
                    @disabled(!$active)
                    wire:loading.attr="disabled"
                    wire:target="send"
                    title="{{ $active ? 'Send' : '' }}"
                    class="inline-flex items-center justify-center h-11 w-11 lg:h-12 lg:w-12 rounded-full
                           bg-blue-500 text-white dark:bg-blue-600 shadow-md
                           enabled:hover:bg-blue-600 enabled:dark:hover:bg-blue-700 enabled:hover:cursor-pointer
                           disabled:opacity-40 disabled:cursor-not-allowed disabled:pointer-events-none"
                >
                    <!-- Loading spinner -->
                    <svg wire:loading wire:target="send" class="animate-spin h-5 w-5 lg:h-6 lg:w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <!-- Send icon -->
                    <svg wire:loading.remove wire:target="send" class="h-5 w-5 lg:h-6 lg:w-6 -mr-0.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2 .01 7z"/>
                    </svg>
                </button>

            </div>
        </footer>

        <!-- Upload Modal -->
        <div
            x-data="{
                open: false,
                init() {
                    // Listen for file selection to open modal instantly
                    this.$watch('$wire.uploads', (uploads) => {
                        if (uploads && uploads.length > 0) {
                            this.open = true;
                        } else if (uploads && uploads.length === 0 && this.open) {
                            // Close modal when uploads are cleared after sending
                            this.open = false;
                        }
                    });
                }
            }"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-[70] flex items-center justify-center"
            aria-modal="true" role="dialog"
        >
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40"></div>

            <!-- Dialog with drag-drop support -->
            <div
                x-data="{
                    dragOver: false,
                    
                    handleDragOver(event) {
                        event.preventDefault();
                        this.dragOver = true;
                    },
                    
                    handleDragLeave(event) {
                        event.preventDefault();
                        // Only set dragOver to false if we're leaving the container itself
                        if (!event.currentTarget.contains(event.relatedTarget)) {
                            this.dragOver = false;
                        }
                    },
                    
                    handleDrop(event) {
                        event.preventDefault();
                        this.dragOver = false;
                        const files = event.dataTransfer?.files;
                        if (files?.length) {
                            this.addFiles(files);
                        }
                    },
                    
                    addFiles(files) {
                        // Check if we should replace existing files or add to them
                        const shouldReplace = $refs.file.files?.length > 0;
                        
                        if (shouldReplace) {
                            // Replace existing files
                            const dt = new DataTransfer();
                            Array.from(files).forEach(f => dt.items.add(f));
                            $refs.file.files = dt.files;
                        } else {
                            // Add to existing files (if any)
                            const dt = new DataTransfer();
                            if ($refs.file.files?.length) {
                                Array.from($refs.file.files).forEach(f => dt.items.add(f));
                            }
                            Array.from(files).forEach(f => dt.items.add(f));
                            $refs.file.files = dt.files;
                        }
                        
                        $refs.file.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }"
                @dragover="handleDragOver($event)"
                @dragleave="handleDragLeave($event)"
                @drop="handleDrop($event)"
                :class="dragOver ? 'ring-2 ring-blue-400/50' : ''"
                class="relative w-[92vw] max-w-xl rounded-2xl bg-white dark:bg-neutral-900 shadow-2xl border border-neutral-200 dark:border-neutral-700 overflow-hidden transition-all duration-200"
                x-trap.noscroll="open"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 dark:border-neutral-800">
                    <div class="font-semibold text-neutral-800 dark:text-neutral-100">
                        {{ count($uploads ?? []) }} files selected
                    </div>
                    <button
                        class="p-2 rounded-full hover:bg-neutral-100 dark:hover:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="open=false; $wire.cancelUploads()"
                        wire:loading.attr="disabled"
                        wire:target="confirmSendFromModal"
                        aria-label="Close"
                    >
                        <svg class="h-5 w-5 text-neutral-600 dark:text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="relative p-4 space-y-4 max-h-[70vh] overflow-y-auto">
                    <!-- Loading overlay -->
                    <div wire:loading wire:target="confirmSendFromModal" class="absolute inset-0 bg-white/70 dark:bg-neutral-900/70 backdrop-blur-sm z-10 flex items-center justify-center rounded-lg">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Uploading files...</div>
                        </div>
                    </div>
                    <!-- File list -->
                    <div class="space-y-2">
                        @foreach($uploads as $file)
                            @php
                                $isImg = str_starts_with($file->getMimeType(), 'image/');
                                $name = $file->getClientOriginalName();
                                $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                $sizeMB = number_format($file->getSize() / 1048576, 2);
                                $map = [
                                    'pdf' => ['ring' => 'ring-red-200 dark:ring-red-500/30', 'fg' => 'text-red-600 dark:text-red-300', 'label' => 'PDF'],
                                    'zip' => ['ring' => 'ring-amber-200 dark:ring-amber-500/30', 'fg' => 'text-amber-700 dark:text-amber-300', 'label' => 'ZIP'],
                                    'txt' => ['ring' => 'ring-sky-200 dark:ring-sky-500/30', 'fg' => 'text-sky-700 dark:text-sky-300', 'label' => 'TXT'],
                                    'doc' => ['ring' => 'ring-blue-200 dark:ring-blue-500/30', 'fg' => 'text-blue-700 dark:text-blue-300', 'label' => 'DOC'],
                                    'docx'=> ['ring' => 'ring-blue-200 dark:ring-blue-500/30', 'fg' => 'text-blue-700 dark:text-blue-300', 'label' => 'DOCX'],
                                ];
                                $sty = $map[$ext] ?? ['ring' => 'ring-neutral-200 dark:ring-neutral-600/40', 'fg' => 'text-neutral-700 dark:text-neutral-300', 'label' => strtoupper($ext ?: 'FILE')];
                            @endphp

                            <div class="flex items-center gap-3 rounded-xl border border-neutral-200 dark:border-neutral-700 p-2">
                                @if($isImg)
                                    <img src="{{ $file->temporaryUrl() }}" class="h-12 w-12 rounded-md object-cover border border-neutral-200 dark:border-neutral-700" />
                                @else
                                    <div class="h-12 w-12 shrink-0 rounded-md ring-1 {{ $sty['ring'] }} flex items-center justify-center">
                                        <span class="text-xs font-semibold {{ $sty['fg'] }}">{{ $sty['label'] }}</span>
                                    </div>
                                @endif

                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $name }}</div>
                                    <div class="text-[11px] text-neutral-500 dark:text-neutral-400">{{ $sizeMB }} MB</div>
                                </div>

                                <button
                                    type="button"
                                    class="p-1.5 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-800"
                                    wire:click="removeUploadByName(@js($file->getFilename()))"
                                    title="Remove"
                                >
                                    <svg class="h-4 w-4 text-neutral-600 dark:text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between gap-4">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" class="rounded border-neutral-300 dark:border-neutral-700"
                                   wire:model.live="groupItems">
                            <span class="text-sm text-neutral-700 dark:text-neutral-300">Group items</span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" class="rounded border-neutral-300 dark:border-neutral-700"
                                   wire:model.live="compressImages">
                            <span class="text-sm text-neutral-700 dark:text-neutral-300">Compress images</span>
                        </label>
                    </div>

                    <!-- Comment (uses your messageText) -->
                    <div>
                        <label class="block text-xs mb-1 text-neutral-500 dark:text-neutral-400">Comment</label>
                        <textarea
                            wire:model.defer="messageText"
                            rows="2"
                            autocomplete="off"
                            @message-sent.window="setTimeout(() => { $el.value = ''; }, 100)"
                            class="w-full rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="Add a comment…"></textarea>
                    </div>
                </div>

                <!-- Footer actions -->
                <div class="flex items-center justify-end gap-2 px-4 py-3 border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/60">
                    <button
                        class="px-3 py-2 text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="document.getElementById('chat-file-input')?.click()"
                        wire:loading.attr="disabled"
                        wire:target="confirmSendFromModal"
                    >Add</button>

                    <button
                        class="px-3 py-2 text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="open=false; $wire.cancelUploads()"
                        wire:loading.attr="disabled"
                        wire:target="confirmSendFromModal"
                    >Cancel</button>

                    <button
                        class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        wire:click="confirmSendFromModal"
                        wire:loading.attr="disabled"
                        wire:target="confirmSendFromModal"
                    >
                        <svg wire:loading wire:target="confirmSendFromModal" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="confirmSendFromModal">Send</span>
                        <span wire:loading wire:target="confirmSendFromModal">Sending...</span>
                    </button>
                </div>
            </div>
        </div>

    </section>

    <!-- Global Drag Overlay -->
    <div x-show="globalDragOver" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-blue-500/20 backdrop-blur-sm flex items-center justify-center pointer-events-none">
        <div class="bg-white dark:bg-neutral-800 rounded-2xl p-8 shadow-2xl border-2 border-dashed border-blue-400 text-center">
            <div class="h-16 w-16 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-2">Drop files here</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Release to upload files to the chat</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        // Preserve scroll position when older messages prepend
        Livewire.on('older-loaded', () => {
            const el = document.getElementById('messages-box');
            if (el) {
                const prevH = window.__ac_prevH || 0;
                const delta = el.scrollHeight - prevH;
                el.scrollTop += delta;
                window.__ac_prevH = 0;
            }
        });
    });

    function fileCard({srcUrl, filename}) {
        return {
            srcUrl, filename,
            showSaveModal: false,

            guessMime() {
                const n = (this.filename || '').toLowerCase();
                if (n.endsWith('.pdf')) return 'application/pdf';
                if (/\.(png|jpg|jpeg|webp|gif)$/i.test(n)) return 'image/' + n.split('.').pop();
                if (n.endsWith('.txt')) return 'text/plain';
                if (/\.(mp3|wav|ogg)$/i.test(n)) return 'audio/mpeg';
                if (/\.(mp4|webm|ogv)$/i.test(n)) return 'video/mp4';
                return 'application/octet-stream';
            },
            canPreview(m) {
                return m.startsWith('image/')
                    || m.startsWith('audio/')
                    || m.startsWith('video/')
                    || m === 'application/pdf'
                    || m === 'text/plain';
            },

            async openOrSave() {
                const resp = await fetch(this.srcUrl, {credentials: 'same-origin'});
                const blob = await resp.blob();
                const mime = blob.type || this.guessMime();
                const url = URL.createObjectURL(blob);

                if (this.canPreview(mime)) {
                    window.open(url, '_blank', 'noopener,noreferrer');
                } else {
                    this._save(url);
                }
                setTimeout(() => URL.revokeObjectURL(url), 60000);
            },

            async onContextMenu() {
                const resp = await fetch(this.srcUrl, {credentials: 'same-origin'});
                const blob = await resp.blob();
                const mime = blob.type || this.guessMime();

                if (this.canPreview(mime)) {
                    // Show save modal for previewable files
                    this.showSaveModal = true;
                } else {
                    // Non-preview able → just save
                    const url = URL.createObjectURL(blob);
                    this._save(url);
                    setTimeout(() => URL.revokeObjectURL(url), 60000);
                }
            },

            async confirmSave() {
                const resp = await fetch(this.srcUrl, {credentials: 'same-origin'});
                const blob = await resp.blob();
                const url = URL.createObjectURL(blob);
                this._save(url);
                setTimeout(() => URL.revokeObjectURL(url), 60000);
                this.showSaveModal = false;
            },

            _save(url) {
                const a = document.createElement('a');
                a.href = url;
                a.download = this.filename || 'download';
                document.body.appendChild(a);
                a.click();
                a.remove();
            }
        };
    }

    function adminEcho(sid) {
        return {
            sid,              // two-way bound to $wire.selectedId
            current: null,    // currently subscribed conversation id
            channel: null,

            boot() {
                // subscribe immediately if sid already set
                if (this.sid) this.subscribe(this.sid);
                // watch for conversation changes from Livewire
                this.$watch('sid', (id) => this.subscribe(id));

                // Cleanup on page navigation
                document.addEventListener('livewire:navigating', () => {
                    this.cleanup();
                });
            },

            cleanup() {
                if (this.current) {
                    try {
                        window.Echo.leave(`private-conversation.${this.current}`);
                        console.log('🧹 admin cleanup: left conversation', this.current);
                    } catch (_) {}
                    this.current = null;
                }
            },

            subscribe(id) {
                // leave previous channel if exists
                if (this.current) {
                    try {
                        window.Echo.leave(`private-conversation.${this.current}`);
                        console.log('🚪 admin left conversation', this.current);
                    } catch (_) {}
                }

                // If no id provided, just clear current and don't subscribe to anything
                if (!id) {
                    this.current = null;
                    return;
                }

                this.current = id;

                // (re)subscribe
                const ch = window.Echo.private(`conversation.${id}`);
                ch.subscribed(() => console.log('✅ admin subscribed to', id));

                // avoid duplicate handlers on reconnects
                ch.stopListening('MessageSent')
                    .listen('MessageSent', () => {
                        try {
                            if (this.$wire && typeof this.$wire.messageReceived === 'function') {
                                this.$wire.messageReceived();
                            }
                        } catch (error) {
                            console.error('Error calling messageReceived:', error);
                        }
                    });

                ch.stopListening('UserTyping')
                    .listen('UserTyping', (e) => {
                        try {
                            if (this.$wire && typeof this.$wire.userTypingReceived === 'function') {
                                this.$wire.userTypingReceived(e);
                            }
                        } catch (error) {
                            console.error('Error calling userTypingReceived:', error);
                        }
                    });
            }
        }
    }
</script>
