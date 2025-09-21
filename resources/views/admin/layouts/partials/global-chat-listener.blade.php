<script>
document.addEventListener('livewire:init', () => {
    // Global admin chat listener - works regardless of which page the admin is on
    let adminChannel = null;
    
    // Function to setup Echo listener
    const setupEchoListener = () => {
        if (window.Echo && window.broadcastConnectionStatus === 'connected') {
            try {
                adminChannel = window.Echo.private('admin-chat');
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
        if (adminChannel) {
            try {
                adminChannel.stopListening('MessageSent');
                console.log('🔌 Global admin chat listener disconnected');
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
