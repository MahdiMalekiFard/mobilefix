<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200/70 dark:border-zinc-800 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-200/70 dark:border-zinc-800 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">Support Chat</h2>
        </div>

        <div wire:poll.5s="loadMessages" class="h-96 overflow-y-auto p-4 space-y-3" id="chat-scroll">
            @forelse($chatMessages ?? [] as $msg)
                <div class="flex {{ $msg->sender_type === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] px-4 py-2 rounded-2xl {{ $msg->sender_type === 'user' ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-bl-sm' }}">
                        <div class="text-sm whitespace-pre-line">{{ $msg->body }}</div>
                        <div class="text-[10px] mt-1 opacity-70">{{ $msg->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-sm text-zinc-500 py-8">No messages yet. Say hello!</div>
            @endforelse
        </div>

        <form wire:submit.prevent="sendMessage" class="p-4 border-t border-zinc-200/70 dark:border-zinc-800">
            <div class="flex items-end gap-3">
                <textarea
                    wire:model.defer="newMessage"
                    rows="2"
                    class="flex-1 resize-none rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    placeholder="Type your message..."
                ></textarea>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 active:translate-y-[1px]">
                    Send
                </button>
            </div>
            @error('newMessage')
                <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
            @enderror
        </form>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.added', ({ el }) => {
            if (el.id === 'chat-scroll') {
                el.scrollTop = el.scrollHeight;
            }
        });
    });
    document.addEventListener('livewire:navigated', () => {
        const el = document.getElementById('chat-scroll');
        if (el) el.scrollTop = el.scrollHeight;
    });
</script>


