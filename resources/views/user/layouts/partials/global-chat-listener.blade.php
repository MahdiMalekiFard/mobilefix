<script>
document.addEventListener('livewire:init', () => {
    // Global user chat listener - works regardless of which page the user is on
    let userChannel = null;
    
    // Function to setup Echo listener
    const setupEchoListener = () => {
        if (window.Echo && window.broadcastConnectionStatus === 'connected') {
            try {
                userChannel = window.Echo.private('user-chat.{{ auth()->id() }}');
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
            } catch (error) {
                console.warn('⚠️ Failed to setup Echo listener:', error);
            }
        } else {
            console.log('ℹ️ Echo not available or not connected - real-time updates disabled');
        }
    };
    
    // Setup listener immediately if Echo is already connected
    setupEchoListener();
    
    // Setup listener when Echo connects
    document.addEventListener('echo-connected', setupEchoListener);
    
    // Cleanup when Echo disconnects
    document.addEventListener('echo-disconnected', () => {
        if (userChannel) {
            try {
                userChannel.stopListening('MessageSent');
                console.log('🔌 Global user chat listener disconnected');
            } catch (error) {
                console.warn('⚠️ Error cleaning up Echo listener:', error);
            }
        }
    });
    
    // Handle Echo errors
    document.addEventListener('echo-error', (event) => {
        console.warn('⚠️ Echo connection error - real-time updates disabled:', event.detail);
    });
    
    document.addEventListener('echo-failed', (event) => {
        console.warn('⚠️ Echo initialization failed - real-time updates disabled:', event.detail);
    });
});
</script>
