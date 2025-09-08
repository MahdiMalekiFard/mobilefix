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
             x-data="{ atBottom: true }"
             x-ref="box"
             @scroll-bottom.window="$nextTick(() => { $refs.box.scrollTo({ top: $refs.box.scrollHeight, behavior: 'smooth' }); })"
             @scroll.passive="atBottom = ($el.scrollTop + $el.clientHeight) >= ($el.scrollHeight - 50)"
             @dragover.prevent
             @drop.prevent="
                const files = $event.dataTransfer?.files;
                if (files?.length) $dispatch('chat-files-dropped', { files });
             "
             class="relative flex-1 overflow-y-auto overflow-x-hidden px-4 lg:px-6 py-4 lg:py-6 min-h-0 bg-white dark:bg-neutral-900">

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
                                $isMine = $m->sender_id === auth()->id();
                                $media = $m->getMedia('attachments');
                            @endphp
                            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}" wire:key="m-{{ $m->id }}">
                                <div class="relative max-w-[85%] lg:max-w-[70%]">
                                    <div class="{{ $isMine
                                        ? 'bg-blue-500 text-white rounded-2xl rounded-br-none'
                                        : 'bg-neutral-100 text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100 rounded-2xl rounded-bl-none'
                                        }} px-4 py-3 shadow-sm break-words">

                                        {{-- text --}}
                                        @if(!empty($m->body))
                                            <p class="whitespace-pre-line">{{ $m->body }}</p>
                                        @endif

                                        {{-- attachments --}}
                                        @if($media->isNotEmpty())
                                            <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                @foreach($media as $md)
                                                    @if(str_starts_with($md->mime_type, 'image/'))
                                                        <a href="{{ $md->hasGeneratedConversion('preview') ? $md->getFullUrl('preview') : $md->getFullUrl() }}"
                                                           target="_blank" rel="noopener noreferrer" class="block">
                                                            <img src="{{ $md->hasGeneratedConversion('thumb') ? $md->getUrl('thumb') : $md->getFullUrl() }}"
                                                                 alt="{{ $md->file_name }}"
                                                                 class="w-full h-28 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700">
                                                        </a>
                                                    @else
                                                        @php
                                                            $ext = strtolower(pathinfo($md->file_name, PATHINFO_EXTENSION));
                                                            $mime = $md->mime_type ?? '';
                                                            $isArchive = in_array($ext, ['zip','rar','7z']) || str_contains($mime, 'zip') || str_contains($mime, 'x-rar') || str_contains($mime, 'x-7z-compressed');

                                                            // simple color/icon map by extension
                                                            $types = [
                                                                'pdf' => [
                                                                    'bubble' => 'bg-red-50 dark:bg-red-400/10 ring-red-200 dark:ring-red-500/30',
                                                                    'fg'     => 'text-red-600 dark:text-red-300',
                                                                    'icon'   => 'pdf',
                                                                ],
                                                                'zip' => [
                                                                    'bubble' => 'bg-amber-50 dark:bg-amber-400/10 ring-amber-200 dark:ring-amber-500/30',
                                                                    'fg'     => 'text-amber-700 dark:text-amber-300',
                                                                    'icon'   => 'zip',
                                                                ],
                                                                'txt' => [
                                                                    'bubble' => 'bg-sky-50 dark:bg-sky-400/10 ring-sky-200 dark:ring-sky-500/30',
                                                                    'fg'     => 'text-sky-700 dark:text-sky-300',
                                                                    'icon'   => 'txt',
                                                                ],
                                                                'doc' => [
                                                                    'bubble' => 'bg-blue-50 dark:bg-blue-400/10 ring-blue-200 dark:ring-blue-500/30',
                                                                    'fg'     => 'text-blue-700 dark:text-blue-300',
                                                                    'icon'   => 'doc',
                                                                ],
                                                                'docx' => [
                                                                    'bubble' => 'bg-blue-50 dark:bg-blue-400/10 ring-blue-200 dark:ring-blue-500/30',
                                                                    'fg'     => 'text-blue-700 dark:text-blue-300',
                                                                    'icon'   => 'doc',
                                                                ],
                                                                // default/generic
                                                                '*' => [
                                                                    'bubble' => 'bg-neutral-50 dark:bg-neutral-700/20 ring-neutral-200 dark:ring-neutral-600/40',
                                                                    'fg'     => 'text-neutral-700 dark:text-neutral-300',
                                                                    'icon'   => 'file',
                                                                ],
                                                            ];
                                                            $style = $types[$ext] ?? $types['*'];

                                                            $previewUrl  = $md->getFullUrl(); // open in new tab
                                                            $downloadUrl = route('media.download', $md);

                                                            $sizeMB = number_format($md->size / 1048576, 2);
                                                        @endphp
                                                        <div class="group flex items-center gap-3 w-full max-w-[520px]
                                                            rounded-xl border border-neutral-200 dark:border-neutral-700
                                                            bg-white/80 dark:bg-neutral-800/70 px-3 py-2
                                                            hover:bg-neutral-50 dark:hover:bg-neutral-800 transition"
                                                        >
                                                            {{-- icon bubble --}}
                                                            <div class="shrink-0 h-8 w-8 rounded-lg ring-1 {{ $style['bubble'] }} flex items-center justify-center">
                                                                @switch($style['icon'])
                                                                    @case('pdf')
                                                                        <svg viewBox="0 0 24 24" class="h-4 w-4 {{ $style['fg'] }}" fill="currentColor">
                                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM14 3.5L18.5 8H14V3.5zM8 13h2.5a1.5 1.5 0 0 1 0 3H9.5V18H8v-5zm1.5 1.5H10a.5.5 0 0 0 0-1h-1v1zM12 13h3a1 1 0 1 1 0 2h-2v3h-1v-5zm3 1h-2v1h2a.5.5 0 0 0 0-1zM17 13h1.5a1.5 1.5 0 0 1 0 3H18V18h-1v-5zm1.5 1.5H18v1h.5a.5.5 0 0 0 0-1z"/>
                                                                        </svg>
                                                                        @break
                                                                    @case('zip')
                                                                        <svg viewBox="0 0 24 24" class="h-4 w-4 {{ $style['fg'] }}" fill="currentColor">
                                                                            <path d="M7 2h7l4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm6 2v4h4M9 6h2v2H9V6zm0 4h2v2H9v-2zm2 4H9v2h2v-2z"/>
                                                                        </svg>
                                                                        @break
                                                                    @case('txt')
                                                                        <svg viewBox="0 0 24 24" class="h-4 w-4 {{ $style['fg'] }}" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                                            <path d="M14 2v6h6M8 13h8M8 16h8M8 10h4"/>
                                                                        </svg>
                                                                        @break
                                                                    @case('doc')
                                                                        <svg viewBox="0 0 24 24" class="h-4 w-4 {{ $style['fg'] }}" fill="currentColor">
                                                                            <path d="M6 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm8 2v4h4M8 10h8v2H8v-2zm0 4h8v2H8v-2z"/>
                                                                        </svg>
                                                                        @break
                                                                    @default
                                                                        <svg viewBox="0 0 24 24" class="h-4 w-4 {{ $style['fg'] }}" fill="currentColor">
                                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6"/>
                                                                        </svg>
                                                                @endswitch
                                                            </div>

                                                            {{-- name + meta --}}
                                                            <div class="min-w-0 flex-1">
                                                                <div class="flex items-center gap-2">
                                                                    <a href="{{ $previewUrl }}" target="_blank" rel="noopener noreferrer" wire:navigate="false"
                                                                       class="font-medium text-neutral-800 dark:text-neutral-100 hover:underline truncate">
                                                                        {{ $md->file_name }}
                                                                    </a>
                                                                    <span class="shrink-0 text-[10px] uppercase px-1.5 py-0.5 rounded
                                                                                 ring-1 {{ $style['bubble'] }} {{ $style['fg'] }}">
                                                                        {{ $ext ?: 'file' }}
                                                                    </span>
                                                                </div>
                                                                <div class="text-[11px] text-neutral-500 dark:text-neutral-400 mt-0.5">
                                                                    {{ $sizeMB }} MB
                                                                </div>
                                                            </div>

                                                            {{-- actions --}}
                                                            <div class="flex items-center gap-1.5" wire:navigate="false">
                                                                {{-- Only show "open in new tab" for non-archives --}}
                                                                @unless($isArchive)
                                                                    <a href="{{ $previewUrl }}" target="_blank" rel="noopener noreferrer" wire:navigate="false"
                                                                       class="p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700" title="Open in new tab">
                                                                        <svg viewBox="0 0 24 24" class="h-4 w-4 text-neutral-600 dark:text-neutral-300" fill="none" stroke="currentColor" stroke-width="1.7">
                                                                            <path d="M14 3h7v7M10 14L21 3M21 14v7h-7"/>
                                                                        </svg>
                                                                    </a>
                                                                @endunless

                                                                {{-- Always keep Download; for archives this is the only action --}}
                                                                <a href="{{ $downloadUrl }}"
                                                                   wire:navigate="false"
                                                                   class="p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700"
                                                                   title="Download" target="_self">
                                                                    <svg viewBox="0 0 24 24" class="h-4 w-4 text-neutral-600 dark:text-neutral-300"
                                                                         fill="none" stroke="currentColor" stroke-width="1.7">
                                                                        <path d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16"/>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- time --}}
                                        <div class="mt-2 text-[11px] opacity-70 text-right {{ $isMine ? 'text-white/80' : 'text-neutral-500 dark:text-neutral-400' }}">
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
                over:false,
                accept:['image/jpeg','image/png','image/webp','image/gif','application/pdf','text/plain','application/zip','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                maxFiles:5,
                addFiles(files){
                    const filtered = Array.from(files).filter(f => this.accept.length ? (this.accept.includes(f.type) || (this.accept.some(t=>t.startsWith('image/')) && f.type.startsWith('image/'))) : true);
                    const dt = new DataTransfer();
                    if (this.$refs.file.files?.length) Array.from(this.$refs.file.files).forEach(f => dt.items.add(f));
                    filtered.slice(0, this.maxFiles - dt.files.length).forEach(f => dt.items.add(f));
                    this.$refs.file.files = dt.files;
                    this.$refs.file.dispatchEvent(new Event('change', { bubbles:true }));
                }
            }"
            @dragover.prevent="if (enabled) over=true"
            @dragleave.prevent="if (enabled) over=false"
            @drop.prevent="if (enabled) { over=false; if ($event.dataTransfer?.files?.length) addFiles($event.dataTransfer.files) }"
            @chat-files-dropped.window="if (enabled && $event.detail?.files) addFiles($event.detail.files)"
            @focus-composer.window="$nextTick(() => {
                const i = $refs.composer;
                if (!i) return;
                i.focus();
                // place caret at end (or use 0,0 if you prefer start)
                try { i.setSelectionRange(i.value.length, i.value.length); } catch(_) {}
                // ensure it’s visible if the page scrolled
                i.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            })"
            class="border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/40 px-3 lg:px-4 py-3 shrink-0 transition"
            :class="enabled && over ? 'ring-2 ring-blue-400/40' : ''"
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

            <div class="mt-2 flex items-center gap-2 lg:gap-3">
                {{-- hidden file input --}}
                <input type="file" multiple class="hidden" x-ref="file" wire:model="uploads"
                       accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.txt,.zip,.doc,.docx,image/*"/>

                {{-- improved attach button --}}
                <button type="button"
                        @click="$refs.file.click()"
                        @disabled(!$active)
                        aria-disabled="{{ $active ? 'false' : 'true' }}"
                        class="group relative inline-flex h-11 w-11 lg:h-12 lg:w-12 items-center justify-center rounded-full border transition
                            {{ $active
                                ? 'border-neutral-200 dark:border-neutral-700 bg-white hover:cursor-pointer dark:bg-neutral-800 shadow-sm hover:shadow-md hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-blue-500/40'
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
                    x-ref="composer"
                    wire:model.defer="messageText"
                    wire:keydown.enter="send"
                    class="flex-1 rounded-full border
                           border-neutral-200 dark:border-neutral-800
                           bg-white dark:bg-neutral-800
                           text-neutral-800 dark:text-neutral-100
                           placeholder-neutral-400 dark:placeholder-neutral-500
                           px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/60"
                    placeholder="{{ $active ? 'Write Something...' : 'Select a conversation first' }}"/>

                {{-- send --}}
                <button
                    wire:click="send"
                    @disabled(!$active)
                    title="{{ $active ? 'Send' : '' }}"
                    class="inline-flex items-center justify-center h-11 w-11 lg:h-12 lg:w-12 rounded-full
                           bg-blue-500 text-white dark:bg-blue-600 shadow-md
                           enabled:hover:bg-blue-600 enabled:dark:hover:bg-blue-700 enabled:hover:cursor-pointer
                           disabled:opacity-40 disabled:cursor-not-allowed disabled:pointer-events-none"
                >
                    <svg class="h-5 w-5 lg:h-6 lg:w-6 -mr-0.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2 .01 7z"/>
                    </svg>
                </button>

            </div>
        </footer>
    </section>
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
</script>
