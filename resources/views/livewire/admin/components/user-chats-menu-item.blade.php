<div
    x-data="{}"
    class="relative"
>
    <a href="{{ route('admin.chat.index') }}" 
       wire:navigate
       @class([
           'flex items-center gap-3 rounded-lg px-3 py-2 text-white hover:bg-gray-800 transition-colors',
           'bg-gray-800' => $isActive
       ])>
        
        {{-- Icon --}}
        <x-icon name="s-chat-bubble-left-right" class="h-5 w-5" />
        
        {{-- Title --}}
        <span class="flex-1">User Chats</span>
        
        {{-- Notification Badge --}}
        @if($unreadCount > 0)
            <span class="inline-flex items-center justify-center min-w-[20px] h-5 bg-red-500 text-white text-[10px] font-semibold px-1.5 rounded-full shadow-lg">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>
</div>
