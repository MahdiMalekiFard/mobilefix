<div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
    <!-- Left: Conversations Sidebar -->
    <div class="lg:col-span-3">
        <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200/70 dark:border-zinc-800 rounded-2xl overflow-hidden">
            @livewire(\App\Livewire\Admin\Pages\Chat\AdminChatApp::class, ['conversationId' => $conversation->id], key('admin-chat-leftbar-'.$conversation->id))
        </div>
    </div>

    <!-- Center: Chat Panel -->
    <div class="lg:col-span-6">
        <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200/70 dark:border-zinc-800 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-200/70 dark:border-zinc-800 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">{{ $conversation->user->name ?? ('User #'.$conversation->user_id) }}</h2>
            </div>

            <div wire:poll.5s="$refresh" class="h-96 overflow-y-auto p-4 space-y-3" id="admin-chat-scroll">
                @foreach($messages as $msg)
                    <div class="flex {{ $msg->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] px-4 py-2 rounded-2xl {{ $msg->sender_type === 'admin' ? 'bg-emerald-600 text-white rounded-br-sm' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 rounded-bl-sm' }}">
                            <div class="text-sm whitespace-pre-line">{{ $msg->body }}</div>
                            <div class="text-[10px] mt-1 opacity-70">{{ $msg->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <form wire:submit.prevent="sendMessage" class="p-4 border-t border-zinc-200/70 dark:border-zinc-800">
                <div class="flex items-end gap-3">
                    <textarea
                        wire:model.defer="newMessage"
                        rows="2"
                        class="flex-1 resize-none rounded-full border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm px-4"
                        placeholder="Write Something..."
                    ></textarea>
                    <button type="submit" class="px-4 py-2 rounded-full bg-emerald-600 text-white font-medium hover:bg-emerald-700 active:translate-y-[1px]">
                        Send
                    </button>
                </div>
                @error('newMessage')
                    <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
                @enderror
            </form>
        </div>
    </div>

    <!-- Right: User Info Panel -->
    <div class="lg:col-span-3">
        <div class="bg-white dark:bg-zinc-900/60 border border-zinc-200/70 dark:border-zinc-800 rounded-2xl overflow-hidden">
            <div class="p-4 border-b border-zinc-200/70 dark:border-zinc-800">
                <input type="text" placeholder="Search Here..." class="w-full rounded-full border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm px-4">
            </div>
            <div class="p-6 flex flex-col items-center gap-3">
                <img class="h-24 w-24 rounded-full object-cover" src="{{ $conversation->user?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt="">
                <div class="text-base font-semibold">{{ $conversation->user->name ?? ('User #'.$conversation->user_id) }}</div>
                <div class="text-xs text-zinc-500">{{ $conversation->user?->email }}</div>
            </div>
            <div class="grid grid-cols-2 gap-4 px-6">
                <a href="{{ route('admin.chat.show', $conversation) }}" class="flex items-center justify-center gap-2 py-3 rounded-xl border border-zinc-200/70 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-900">
                    <span class="text-sm">Chat</span>
                </a>
                <div class="flex items-center justify-center gap-2 py-3 rounded-xl border border-zinc-200/70 dark:border-zinc-800 opacity-60 cursor-not-allowed">
                    <span class="text-sm">Video Call</span>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2"><span class="i-heroicons-user"></span><span>View Profile</span></div>
                    @if($conversation->user)
                        <a href="{{ route('admin.user.edit', $conversation->user) }}" class="text-emerald-600 hover:underline">Open</a>
                    @endif
                </div>
            </div>
            <div class="px-6 pb-6">
                <div class="text-xs text-zinc-500 mb-2">Attachments</div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-xs">PDF</span>
                    <span class="px-3 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-xs">VIDEO</span>
                    <span class="px-3 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-xs">MP3</span>
                    <span class="px-3 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-xs">IMAGE</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.added', ({ el }) => {
            if (el.id === 'admin-chat-scroll') {
                el.scrollTop = el.scrollHeight;
            }
        });
    });
    document.addEventListener('livewire:navigated', () => {
        const el = document.getElementById('admin-chat-scroll');
        if (el) el.scrollTop = el.scrollHeight;
    });
</script>


