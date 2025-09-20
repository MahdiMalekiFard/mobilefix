<div class="relative">
    <a href="{{ route('user.chat.index') }}" 
       wire:navigate
       @class([
           'flex items-center gap-3 rounded-lg px-3 py-2 text-white hover:bg-gray-800 transition-colors',
           'bg-gray-800' => $isActive
       ])>
        
        {{-- Icon --}}
        <x-icon name="s-chat-bubble-left-right" class="h-5 w-5" />
        
        {{-- Title --}}
        <span class="flex-1">Support Chat</span>
        
        {{-- Notification Badge --}}
        @if($unreadCount > 0 && !$isActive)
            <span class="inline-flex items-center justify-center min-w-[20px] h-5 bg-red-500 text-white text-[10px] font-semibold px-1.5 rounded-full shadow-sm absolute right-2 top-1/2 -translate-y-1/2">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>
</div>
