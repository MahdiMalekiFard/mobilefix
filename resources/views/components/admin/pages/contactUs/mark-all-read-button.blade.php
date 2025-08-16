@if($this->getUnreadCount() > 0)
    <div class="d-flex justify-content-end mb-3">
        <button 
            type="button" 
            class="row-actions btn btn-success btn-sm"
            wire:click="markAllAsRead"
            wire:loading.attr="disabled"
            wire:loading.class="disabled"
        >
            <i class="fas fa-check-double me-2"></i>
            Mark All as Read ({{ $this->getUnreadCount() }})
            <span wire:loading wire:target="markAllAsRead">
                <i class="fas fa-spinner fa-spin ms-2"></i>
            </span>
        </button>
    </div>
@endif
