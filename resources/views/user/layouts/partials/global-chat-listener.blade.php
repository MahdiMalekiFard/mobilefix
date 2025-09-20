<script>
document.addEventListener('livewire:init', () => {
    // Global user chat listener - works regardless of which page the user is on
    if (window.Echo) {
        const userChannel = window.Echo.private('user-chat.{{ auth()->id() }}');
        userChannel.subscribed(() => console.log('✅ Global user chat listener subscribed'));
        
        userChannel.stopListening('MessageSent')
            .listen('MessageSent', () => {
                console.log('📩 User received admin message - updating badge');
                // Dispatch Livewire event to update badge
                try {
                    if (window.Livewire && window.Livewire.dispatch) {
                        window.Livewire.dispatch('admin-message-received');
                        console.log('📤 Dispatched admin-message-received Livewire event');
                    } else {
                        console.warn('⚠️ Livewire not available, skipping event dispatch');
                    }
                } catch (error) {
                    console.error('❌ Error dispatching Livewire event:', error);
                }
            });
    }
});
</script>
