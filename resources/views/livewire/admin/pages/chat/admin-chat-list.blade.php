<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">User Chats</h1>
        <div class="relative">
            <input type="text" wire:model.debounce.400ms="search" placeholder="Search users..." class="rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($conversations as $conv)
            <a href="{{ route('admin.chat.show', $conv) }}" class="block rounded-xl border border-zinc-200/70 dark:border-zinc-800 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-900">
                <div class="font-medium">{{ $conv->user->name ?? ('User #'.$conv->user_id) }}</div>
                <div class="text-xs text-zinc-500">Last: {{ optional($conv->last_message_at)->diffForHumans() ?: '—' }}</div>
            </a>
        @endforeach
    </div>
    <div>
        {{ $conversations->links() }}
    </div>
</div>


