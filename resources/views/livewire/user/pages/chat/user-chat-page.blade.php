<div class="@container h-[100vh] flex flex-col">
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
    <div class="bg-white dark:bg-neutral-900/95 flex flex-col h-full @container-normal">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-neutral-200/70 dark:border-neutral-800 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-neutral-800 dark:to-neutral-700 shrink-0">
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

        <!-- Messages Area -->
        <div 
            id="messages-container"
            x-data="{ atBottom: true }"
            x-ref="messagesBox"
            @scroll.passive="atBottom = ($el.scrollTop + $el.clientHeight) >= ($el.scrollHeight - 50)"
            class="flex-1 overflow-y-auto px-4 @lg:px-6 py-4 @lg:py-6 min-h-0 bg-gradient-to-b from-neutral-50/50 to-white dark:from-neutral-900 dark:to-neutral-900 scrollbar-hide"
        >
            @forelse($chatMessages ?? [] as $msg)
                @php $isUser = $msg->sender_type === 'user'; @endphp
                <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }} mb-4 message-bubble" wire:key="msg-{{ $msg->id }}">
                    <div class="relative max-w-[85%] @lg:max-w-[70%]">
                        <!-- Message Bubble -->
                        <div class="px-4 py-3 break-words shadow-sm leading-relaxed relative focus:outline-2 focus:outline-blue-500 focus:outline-offset-2
                            {{ $isUser 
                                ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl rounded-br-none' 
                                : 'bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 rounded-2xl rounded-bl-none border border-neutral-200 dark:border-neutral-700' 
                            }}">
                            
                            <!-- Avatar for admin messages -->
                            @if(!$isUser)
                                <div class="absolute -left-10 bottom-0">
                                    <div class="h-8 w-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center shadow-md">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif

                            <!-- Message Content -->
                            <div class="whitespace-pre-line">{{ $msg->body }}</div>
                            
                            <!-- Timestamp -->
                            <div class="mt-2 text-[11px] opacity-75 
                                {{ $isUser ? 'text-blue-100' : 'text-neutral-500 dark:text-neutral-400' }}">
                                {{ $msg->created_at->format('g:i A') }}
                                @if($isUser)
                                    <span class="ml-1">
                                        <svg class="inline h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>

                            <!-- Chat Tail -->
                            @if($isUser)
                                <div class="absolute bottom-0 right-0 w-4 h-4 bg-blue-600 transform rotate-45 translate-x-2 translate-y-2 rounded-sm"></div>
                            @else
                                <div class="absolute bottom-0 left-0 w-4 h-4 bg-white dark:bg-neutral-800 border-l border-b border-neutral-200 dark:border-neutral-700 transform rotate-45 -translate-x-2 translate-y-2 rounded-sm"></div>
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

            <!-- Typing indicator (placeholder for when admin is typing) -->
            @if($adminIsTyping)
                <div class="flex justify-start mb-4">
                    <div class="relative max-w-[85%] @lg:max-w-[70%]">
                        <div class="px-4 py-3 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 rounded-2xl rounded-bl-none border border-neutral-200 dark:border-neutral-700">
                            <div class="flex items-center gap-2">
                                <div class="flex gap-1">
                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                                    <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                                </div>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">Support is typing...</span>
                            </div>
                            
                            <!-- Chat tail for typing indicator -->
                            <div class="absolute bottom-0 left-0 w-4 h-4 bg-white dark:bg-neutral-800 border-l border-b border-neutral-200 dark:border-neutral-700 transform rotate-45 -translate-x-2 translate-y-2 rounded-sm"></div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Scroll anchor -->
            <div id="messages-end"></div>

            <!-- Scroll to bottom button -->
            <div class="sticky bottom-4 flex justify-center pointer-events-none">
                <button
                    x-cloak
                    x-show="!atBottom"
                    x-transition.opacity.duration.200ms
                    @click="$refs.messagesBox.scrollTo({ top: $refs.messagesBox.scrollHeight, behavior: 'smooth' })"
                    class="pointer-events-auto h-10 w-10 flex items-center justify-center rounded-full 
                           bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 
                           hover:bg-neutral-50 dark:hover:bg-neutral-700 shadow-lg text-neutral-600 dark:text-neutral-300
                           transition-colors duration-200"
                    title="Scroll to bottom"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <footer class="border-t border-neutral-200 dark:border-neutral-800
                       bg-neutral-50 dark:bg-neutral-900/40 px-3 @lg:px-4 py-3 shrink-0">
            <div class="flex items-center gap-2 @lg:gap-3">
                <input
                    wire:model.defer="messageText"
                    wire:keydown.enter="send"
                    class="flex-1 rounded-full border
                           border-neutral-200 dark:border-neutral-800
                           bg-white dark:bg-neutral-800
                           text-neutral-800 dark:text-neutral-100
                           placeholder-neutral-400 dark:placeholder-neutral-500
                           px-4 py-3 outline-none focus:outline-2 focus:outline-blue-500 focus:outline-offset-2"
                    placeholder="Write Something..." />
                <button
                    class="inline-flex items-center justify-center h-11 w-11 @lg:h-12 @lg:w-12 rounded-full
                           bg-blue-500 hover:bg-blue-600 text-white dark:bg-blue-600 dark:hover:bg-blue-700
                           shadow-md disabled:opacity-40 hover:cursor-pointer transition-colors"
                    wire:click="send" 
                    title="Send">
                    <svg class="h-5 w-5 @lg:h-6 @lg:w-6 -mr-0.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2 .01 7z"/>
                    </svg>
                </button>
            </div>
            
        </footer>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        // Auto-scroll to bottom ONLY when user sends a message
        Livewire.on('message-sent', () => {
            const el = document.getElementById('messages-container');
            if (el) {
                setTimeout(() => {
                    el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
                }, 150);
            }
            
            // Input clears automatically with wire:model.defer
        });

        // Show notification if window is not focused when message received
        Livewire.on('message-received', () => {
            if (!document.hasFocus()) {
                if (Notification.permission === 'granted') {
                    new Notification('New message from Support', {
                        body: 'You have received a new message',
                        icon: '/favicon.ico'
                    });
                }
            }
        });

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Auto-focus on input when page loads
        setTimeout(() => {
            const input = document.querySelector('input[wire\\:model\\.defer="messageText"]');
            if (input) input.focus();
        }, 500);
    });

    document.addEventListener('livewire:navigated', () => {
        // Auto-focus on input after navigation
        setTimeout(() => {
            const input = document.querySelector('input[wire\\:model\\.defer="messageText"]');
            if (input) input.focus();
        }, 500);
    });

    // Enter key handling is now handled by wire:keydown.enter="send" in the template
</script>