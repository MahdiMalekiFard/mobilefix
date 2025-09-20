<div>
    @if($unreadCount > 0 && !$isActive)
        <span class="inline-flex items-center justify-center min-w-[20px] h-5 bg-red-500 text-white text-[10px] font-semibold px-1.5 rounded-full shadow-sm absolute right-2 top-1/2 -translate-y-1/2">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
    @endif
</div>
