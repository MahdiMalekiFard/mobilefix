<div class="@container h-full min-h-0 flex flex-col">
    <style>
        @layer utilities {
            .message-bubble { @starting-style { opacity: 0; transform: translateY(10px);} transition: all 300ms ease-out; }
            .scrollbar-hide { scrollbar-width: none; &::-webkit-scrollbar { display: none; } }
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
                @php $isUser = $msg->sender_type === 'user'; @endphp
                <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }} mb-4 message-bubble" wire:key="msg-{{ $msg->id }}">
                    <div class="relative {{ $isUser ? '' : 'pl-10' }} max-w-[85%] @lg:max-w-[70%]">
                        @unless($isUser)
                            <div class="absolute left-0 bottom-2">
                                <div class="h-8 w-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center shadow-md">
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                        @endunless

                        <div class="px-4 py-3 break-words shadow-sm leading-relaxed relative focus:outline-2 focus:outline-blue-500 focus:outline-offset-2
                            {{ $isUser
                                ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl rounded-br-none'
                                : 'bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 rounded-2xl rounded-bl-none border border-neutral-200 dark:border-neutral-700'
                            }}">
                            <div class="whitespace-pre-line">{{ $msg->body }}</div>

                            <div class="mt-2 text-[11px] opacity-75 {{ $isUser ? 'text-blue-100' : 'text-neutral-500 dark:text-neutral-400' }}">
                                {{ $msg->created_at->format('g:i A') }}
                                @if($isUser)
                                    <span class="ml-1">
                                        <svg class="inline h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>

                            {{-- Attachments --}}
                            @php $media = $msg->getMedia('attachments'); @endphp
                            @if($media->isNotEmpty())
                                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($media as $m)
                                        @if(str_starts_with($m->mime_type, 'image/'))
                                            <a href="{{ $m->hasGeneratedConversion('preview') ? $m->getFullUrl('preview') : $m->getFullUrl() }}"
                                               target="_blank" rel="noopener noreferrer" class="block">
                                                <img src="{{ $m->hasGeneratedConversion('thumb') ? $m->getUrl('thumb') : $m->getFullUrl() }}"
                                                     alt="{{ $m->file_name }}"
                                                     class="w-full h-28 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700">
                                            </a>
                                        @else
                                            @php
                                                $downloadUrl = \Illuminate\Support\Facades\Route::has('media.download')
                                                    ? route('media.download', $m)
                                                    : $m->getFullUrl();
                                            @endphp
                                            <a href="{{ $downloadUrl }}" target="_blank" rel="noopener noreferrer"
                                               class="flex items-center gap-2 px-3 py-2 rounded-lg border
                                                      border-neutral-200 dark:border-neutral-700
                                                      bg-white/70 dark:bg-neutral-800/70 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M14.59 2.59L21 9l-7 7-6.41-6.41L14.59 2.59zM3 13l4 4H3v-4z"/></svg>
                                                <span class="truncate text-sm">{{ $m->file_name }}</span>
                                                <span class="ml-auto text-[11px] opacity-70">{{ number_format($m->size/1048576, 2) }} MB</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
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
                               bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700
                               hover:bg-neutral-50 dark:hover:bg-neutral-700 shadow-lg text-neutral-600 dark:text-neutral-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
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
                                <img src="{{ $file->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700" />
                            @else
                                <div class="h-16 w-44 flex items-center gap-2 px-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white/70 dark:bg-neutral-800/70">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M14.59 2.59L21 9l-7 7-6.41-6.41L14.59 2.59zM3 13l4 4H3v-4z"/></svg>
                                    <span class="truncate text-xs">{{ $file->getClientOriginalName() }}</span>
                                </div>
                            @endif
                            <button type="button" class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 text-white text-xs hidden group-hover:flex items-center justify-center"
                                    wire:click="removeUploadByName(@js($tempName))" title="Remove">×</button>
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
                <input type="file" multiple class="hidden" x-ref="file" wire:model="uploads"
                       accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.txt,.zip,.doc,.docx,image/*" />

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
                    placeholder="Write Something..." />

                {{-- send --}}
                <button class="inline-flex items-center justify-center h-11 w-11 @lg:h-12 @lg:w-12 rounded-full
                               bg-blue-500 hover:bg-blue-600 text-white dark:bg-blue-600 dark:hover:bg-blue-700
                               shadow-md disabled:opacity-40 hover:cursor-pointer transition-colors"
                        wire:click="send" title="Send">
                    <svg class="h-5 w-5 @lg:h-6 @lg:w-6 -mr-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2 .01 7z"/></svg>
                </button>
            </div>
        </footer>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('message-sent', () => {
            const el = document.getElementById('messages-container');
            if (el) setTimeout(() => el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' }), 150);
        });

        Livewire.on('message-received', () => {
            if (!document.hasFocus() && 'Notification' in window && Notification.permission === 'granted') {
                new Notification('New message from Support', { body: 'You have received a new message', icon: '/favicon.ico' });
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
</script>
