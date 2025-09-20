<script>
document.addEventListener('livewire:init', () => {
    // Global admin chat listener - works regardless of which page the admin is on
    if (window.Echo) {
        const adminChannel = window.Echo.private('admin-chat');
        adminChannel.subscribed(() => console.log('✅ Global admin chat listener subscribed'));
        
        adminChannel.stopListening('MessageSent')
            .listen('MessageSent', () => {
                console.log('📩 Global listener received MessageSent event');
                // Dispatch Livewire event to all components with error handling
                try {
                    if (window.Livewire && window.Livewire.dispatch) {
                        window.Livewire.dispatch('global-message-received');
                        console.log('📤 Dispatched global-message-received Livewire event');
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
