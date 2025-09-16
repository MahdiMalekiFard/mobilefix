<div class="@container h-full min-h-0 flex flex-col">
    <style>
        @layer utilities {
            .message-bubble {
                @starting-style {
                    opacity: 0;
                    transform: translateY(10px);
                }
                transition: all 300ms ease-out;
            }

            .scrollbar-hide {
                scrollbar-width: none;

                &::-webkit-scrollbar {
                    display: none;
                }
            }
        }
    </style>

    <div class="bg-white dark:bg-neutral-900/95 flex flex-col h-full min-h-0 @container-normal">
        <!-- Header -->
        <div
            class="sticky top-0 z-20 px-6 py-4 border-b border-neutral-200/70 dark:border-neutral-800
                   bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-neutral-800 dark:to-neutral-700
                   backdrop-blur supports-[backdrop-filter]:bg-white/70 dark:supports-[backdrop-filter]:bg-neutral-900/70 shrink-0">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Support Chat</h2>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">Get help from our support team</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-2 w-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-neutral-600 dark:text-neutral-400">Online</span>
                </div>
            </div>
        </div>

        <!-- Messages Area (also acts as a drop target) -->
        <div
            id="messages-container"
            x-data="{ atBottom: true }"
            x-ref="messagesBox"
            x-init="$nextTick(() => {
                $refs.messagesBox.scrollTo({ top: $refs.messagesBox.scrollHeight, behavior: 'smooth' });
            })"
            @ui:scroll-bottom.window="$nextTick(() => {
                  $refs.messagesBox.scrollTo({ top: $refs.messagesBox.scrollHeight, behavior: 'smooth' });
            })"
            @scroll.passive="atBottom = ($el.scrollTop + $el.clientHeight) >= ($el.scrollHeight - 50)"
            @dragover.prevent
            @drop.prevent="
                const files = $event.dataTransfer?.files;
                if (files?.length) $dispatch('chat-files-dropped', { files });
            "
            class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden px-4 @lg:px-6 py-4 @lg:py-6
                   bg-gradient-to-b from-neutral-50/50 to-white dark:from-neutral-900 dark:to-neutral-900">

            {{-- Load older --}}
            @if($this->nextCursor)
                <div class="flex justify-center mb-4">
                    <button
                        wire:click="loadOlder"
                        x-on:click="window.__uc_prevH = $refs.messagesBox?.scrollHeight || 0;"
                        class="text-xs px-3 py-1 rounded-full border border-neutral-300 dark:border-neutral-700
                               hover:bg-neutral-100 dark:hover:bg-neutral-800">
                        Load older messages
                    </button>
                </div>
            @endif

            @forelse($chatMessages ?? [] as $msg)
                @php
                    $isUser   = $msg->sender_type === 'user';
                    $media    = $msg->getMedia('attachments');
                    $images   = $media->filter(fn($m) => str_starts_with($m->mime_type, 'image/'));
                    $files    = $media->reject(fn($m) => str_starts_with($m->mime_type, 'image/'));
                    $hasBody  = trim($msg->body) !== '';
                @endphp

                <div class="flex {{ $isUser ? 'justify-start' : 'justify-end' }} mb-10 message-bubble" wire:key="msg-{{ $msg->id }}">
                    {{-- USER (blue, left) --}}
                    @if($isUser)
                        <div class="flex flex-col gap-2 max-w-[85%] @lg:max-w-[70%] items-start">

                            {{-- TEXT --}}
                            @if($hasBody)
                                <div class="bg-blue-50 ring-1 ring-blue-200 text-blue-900 dark:bg-blue-600/20 dark:ring-blue-400/40 dark:text-blue-100 rounded-2xl rounded-bl-none px-4 py-3 text-sm font-medium">
                                    <div class="whitespace-pre-line">{{ $msg->body }}</div>
                                </div>
                            @endif

                            {{-- IMAGES (align left) --}}
                            @if($images->isNotEmpty())
                                <div class="{{ $images->count() > 1 ? 'grid grid-cols-2 gap-2' : 'flex' }} items-start">
                                    @foreach($images as $im)
                                        <a href="{{ $im->getFullUrl() }}" target="_blank" class="block">
                                            <img src="{{ $im->getFullUrl() }}"
                                                 alt="{{ $im->file_name }}"
                                                 class="w-[260px] h-[160px] object-cover rounded-2xl border border-neutral-200 dark:border-neutral-700"/>
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
                                                    <button class="px-3 py-2 text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800" @click="showSaveModal=false">Cancel</button>
                                                    <button class="px-3 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow" @click="confirmSave">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <div class="text-[11px] opacity-70 text-neutral-500 dark:text-neutral-400 text-left">{{ $msg->created_at->format('g:i A') }}</div>
                        </div>
                    @else
                        {{-- ADMIN (white, right) with avatar on the right for all blocks --}}
                        <div class="flex items-end gap-2 max-w-[85%] @lg:max-w-[70%]">

                            {{-- CONTENT COLUMN (bubble(s) + images + files + time) --}}
                            <div class="flex flex-col items-end gap-2">

                                {{-- TEXT --}}
                                @if($hasBody)
                                    <div class="bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100
                                                rounded-2xl rounded-br-none border border-neutral-200 dark:border-neutral-700 px-4 py-3">
                                        <div class="whitespace-pre-line">{{ $msg->body }}</div>
                                    </div>
                                @endif

                                {{-- IMAGES (kept together) --}}
                                @if($images->isNotEmpty())
                                    <div class="{{ $images->count() > 1 ? 'grid grid-cols-2 gap-2' : 'flex' }} justify-end">
                                        @foreach($images as $im)
                                            <a href="{{ $im->getFullUrl() }}" target="_blank" class="block">
                                                <img src="{{ $im->getFullUrl() }}"
                                                     alt="{{ $im->file_name }}"
                                                     class="w-[260px] h-[160px] object-cover rounded-2xl border border-neutral-200 dark:border-neutral-700"/>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- FILES (stacked cards; NO avatar here) --}}
                                @if($files->isNotEmpty())
                                    <div class="flex flex-col items-end gap-2">
                                        @foreach($files as $md)
                                            @php
                                                $bytes = $md->size;
                                                if ($bytes < 1024) {
                                                    // under 1 KB → show in bytes
                                                    $sizeLabel = $bytes . ' B';
                                                } elseif ($bytes < 1048576) {
                                                    // under 1 MB → show in KB
                                                    $sizeLabel = number_format($bytes / 1024, 0) . ' KB';
                                                } else {
                                                    // 1 MB or more → show in MB with 2 decimals
                                                    $sizeLabel = number_format($bytes / 1048576, 2) . ' MB';
                                                }
                                                $full = $md->file_name;
                                                $ext  = pathinfo($full, PATHINFO_EXTENSION);
                                                $base = pathinfo($full, PATHINFO_FILENAME);
                                                $nameShort = (mb_strlen($base) > 18)
                                                  ? mb_substr($base,0,12).'…'.mb_substr($base,-4).($ext?'.'.$ext:'')
                                                  : $full;

                                                // neutral palette for admin side
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
                                    {{ $msg->created_at->format('g:i A') }}
                                </div>
                            </div>

                            {{-- AVATAR (only once for the whole message) --}}
                            <div class="ml-2 flex-shrink-0 self-end">
                                <div class="h-8 w-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center shadow-md">
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center py-12">
                    <div class="h-16 w-16 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-neutral-700 dark:to-neutral-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-2">Start a conversation</h3>
                    <p class="text-neutral-500 dark:text-neutral-400 max-w-sm">Send us a message and our support team will get back to you as soon as possible.</p>
                </div>
            @endforelse

            @if($adminIsTyping)
                <div class="flex justify-start mb-4">
                    <div class="relative max-w-[85%] @lg:max-w-[70%]">
                        <div class="px-4 py-3 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 rounded-2xl rounded-bl-none border border-neutral-200 dark:border-neutral-700">
                            <div class="flex items-center gap-2">
                                <div class="flex gap-1">
                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce"></div>
                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 120ms;"></div>
                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 240ms;"></div>
                                </div>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">Support is typing...</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div id="messages-end"></div>

            <!-- Scroll to bottom -->
            <div class="sticky bottom-4 flex justify-center pointer-events-none">
                <button x-cloak x-show="!atBottom" x-transition.opacity.duration.200ms
                        @click="$refs.messagesBox.scrollTo({ top: $refs.messagesBox.scrollHeight, behavior: 'smooth' })"
                        class="pointer-events-auto h-10 w-10 flex items-center justify-center rounded-full
                               bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 hover:cursor-pointer
                               hover:bg-neutral-50 dark:hover:bg-neutral-700 shadow-lg text-neutral-600 dark:text-neutral-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Composer with drag & drop -->
        <footer
            x-data="{
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
            @dragover.prevent="over=true"
            @dragleave.prevent="over=false"
            @drop.prevent="over=false; if ($event.dataTransfer?.files?.length) addFiles($event.dataTransfer.files)"
            @chat-files-dropped.window="if ($event.detail?.files) addFiles($event.detail.files)"
            class="border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/40 px-3 @lg:px-4 py-3 shrink-0 transition"
            :class="over ? 'ring-2 ring-blue-400/40' : ''"
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
                                <img src="{{ $file->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700"/>
                            @else
                                <div class="h-16 w-44 flex items-center gap-2 px-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white/70 dark:bg-neutral-800/70">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14.59 2.59L21 9l-7 7-6.41-6.41L14.59 2.59zM3 13l4 4H3v-4z"/>
                                    </svg>
                                    <span class="truncate text-xs">{{ $file->getClientOriginalName() }}</span>
                                </div>
                            @endif
                            <button type="button" class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 text-white text-xs hidden group-hover:flex items-center justify-center"
                                    wire:click="removeUploadByName(@js($tempName))" title="Remove">×
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- upload progress --}}
            <div x-data="{p:0, up:false}" x-on:livewire-upload-start="up=true" x-on:livewire-upload-finish="up=false; p=0" x-on:livewire-upload-error="up=false; p=0" x-on:livewire-upload-progress="p=$event.detail.progress">
                <div x-show="up" class="w-full h-1 bg-neutral-200 dark:bg-neutral-800 rounded overflow-hidden">
                    <div class="h-1 bg-blue-500" :style="`width:${p}%;`"></div>
                </div>
            </div>

            <div class="mt-2 flex items-center gap-2 @lg:gap-3">
                {{-- hidden file input --}}
                <input
                    id="chat-file-input"
                    type="file"
                    multiple
                    class="hidden"
                    x-ref="file"
                    wire:model="newUploads"
                    accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.txt,.zip,.doc,.docx,image/*"
                />

                {{-- improved attach button --}}
                <button type="button"
                        @click="$refs.file.click()"
                        class="group relative inline-flex h-11 w-11 @lg:h-12 @lg:w-12 items-center justify-center
                               rounded-full border border-neutral-200 dark:border-neutral-700
                               bg-white dark:bg-neutral-800 shadow hover:shadow-md
                               hover:bg-neutral-50 dark:hover:bg-neutral-700
                               focus:outline-none focus:ring-2 focus:ring-blue-500/40
                               ring-1 ring-inset ring-neutral-200 dark:ring-neutral-700 transition hover:cursor-pointer"
                        title="Attach files">
                    <svg viewBox="0 0 24 24"
                         class="h-5 w-5 @lg:h-6 @lg:w-6 text-neutral-600 dark:text-neutral-300"
                         fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.44 11.05 12 20.5a6.5 6.5 0 1 1-9.19-9.19l10-10a4.5 4.5 0 1 1 6.36 6.36L8.5 18.5a2.5 2.5 0 1 1-3.54-3.54L15 3"/>
                    </svg>
                </button>

                {{-- text input --}}
                <input
                    wire:model.defer="messageText"
                    wire:keydown.enter="send"
                    class="flex-1 rounded-full border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-800
                           text-neutral-800 dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500
                           px-4 py-3 outline-none focus:outline-2 focus:outline-blue-500 focus:outline-offset-2"
                    placeholder="Write Something..."/>

                {{-- send --}}
                <button class="inline-flex items-center justify-center h-11 w-11 @lg:h-12 @lg:w-12 rounded-full
                               bg-blue-500 hover:bg-blue-600 text-white dark:bg-blue-600 dark:hover:bg-blue-700
                               shadow-md disabled:opacity-40 hover:cursor-pointer transition-colors"
                        wire:click="send" title="Send">
                    <svg class="h-5 w-5 @lg:h-6 @lg:w-6 -mr-0.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2 .01 7z"/>
                    </svg>
                </button>
            </div>
        </footer>

        <!-- Upload Modal -->
        <div
            x-data="{ open: @entangle('showUploadModal').live }"
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
                    over:false,
                    addFiles(files){
                            const dt = new DataTransfer();
                            if ($refs.file.files?.length) Array.from($refs.file.files).forEach(f => dt.items.add(f));
                            Array.from(files).forEach(f => dt.items.add(f));
                            $refs.file.files = dt.files;
                            $refs.file.dispatchEvent(new Event('change', { bubbles:true }));
                    }
                }"
                @dragover.prevent="over=true"
                @dragleave.prevent="over=false"
                @drop.prevent="over=false; if ($event.dataTransfer?.files?.length) addFiles($event.dataTransfer.files)"
                :class="over ? 'ring-2 ring-blue-400/50' : ''"
                class="relative w-[92vw] max-w-xl rounded-2xl bg-white dark:bg-neutral-900 shadow-2xl border border-neutral-200 dark:border-neutral-700 overflow-hidden"
                x-trap.noscroll="open"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 dark:border-neutral-800">
                    <div class="font-semibold text-neutral-800 dark:text-neutral-100">
                        {{ count($uploads ?? []) }} files selected
                    </div>
                    <button
                        class="p-2 rounded-full hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        @click="open=false; $wire.cancelUploads()"
                        aria-label="Close"
                    >
                        <svg class="h-5 w-5 text-neutral-600 dark:text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-4 space-y-4 max-h-[70vh] overflow-y-auto">
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
                                    <img src="{{ $file->temporaryUrl() }}" class="h-12 w-12 rounded-md object-cover border border-neutral-200 dark:border-neutral-700"/>
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
                            class="w-full rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="Add a comment…"></textarea>
                    </div>
                </div>

                <!-- Footer actions -->
                <div class="flex items-center justify-end gap-2 px-4 py-3 border-t border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/60">
                    <button
                        class="px-3 py-2 text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        @click="document.getElementById('chat-file-input')?.click()"
                    >Add
                    </button>

                    <button
                        class="px-3 py-2 text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        @click="$wire.cancelUploads()"
                    >Cancel
                    </button>

                    <button
                        class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow"
                        wire:click="confirmSendFromModal"
                    >Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('message-sent', () => {
            const el = document.getElementById('messages-container');
            if (el) setTimeout(() => el.scrollTo({top: el.scrollHeight, behavior: 'smooth'}), 150);
        });

        Livewire.on('message-received', () => {
            if (!document.hasFocus() && 'Notification' in window && Notification.permission === 'granted') {
                new Notification('New message from Support', {body: 'You have received a new message', icon: '/favicon.ico'});
            }
        });

        Livewire.on('older-loaded', () => {
            const el = document.getElementById('messages-container');
            if (el) {
                const prevH = window.__uc_prevH || 0;
                const delta = el.scrollHeight - prevH;
                el.scrollTop += delta;
                window.__uc_prevH = 0;
            }
        });

        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        setTimeout(() => {
            const input = document.querySelector('input[wire\\:model\\.defer="messageText"]');
            if (input) input.focus();
        }, 500);
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
                console.log('I am here in (openOrSave) function!');
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
                    // Non-previewable → just save
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
</script>
