/**
 * Laravel Echo setup for real-time broadcasting with graceful fallback
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Check if Reverb is properly configured
const isReverbConfigured = () => {
    return import.meta.env.VITE_REVERB_APP_KEY && 
           import.meta.env.VITE_REVERB_HOST && 
           import.meta.env.VITE_REVERB_PORT;
};

// Configuration based on environment
const echoConfig = {
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'reverb-key',
    wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        },
    },
    // Add connection timeout and retry settings
    activityTimeout: 120000, // 2 minutes
    pongTimeout: 30000, // 30 seconds
    unavailableTimeout: 10000, // 10 seconds
};

// Fallback to Pusher if Reverb is not available
if (!import.meta.env.VITE_REVERB_APP_KEY) {
    echoConfig.broadcaster = 'pusher';
    echoConfig.key = import.meta.env.VITE_PUSHER_APP_KEY;
    echoConfig.cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1';
    delete echoConfig.wsHost;
    delete echoConfig.wsPort;
    delete echoConfig.wssPort;
}

// Initialize Echo with error handling
try {
    window.Echo = new Echo(echoConfig);
    
    // Add connection status tracking
    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('✅ Echo connected successfully');
        window.broadcastConnectionStatus = 'connected';
        document.dispatchEvent(new CustomEvent('echo-connected'));
    });
    
    window.Echo.connector.pusher.connection.bind('disconnected', () => {
        console.log('⚠️ Echo disconnected');
        window.broadcastConnectionStatus = 'disconnected';
        document.dispatchEvent(new CustomEvent('echo-disconnected'));
    });
    
    window.Echo.connector.pusher.connection.bind('error', (error) => {
        console.log('❌ Echo connection error:', error);
        window.broadcastConnectionStatus = 'error';
        document.dispatchEvent(new CustomEvent('echo-error', { detail: error }));
    });
    
    window.Echo.connector.pusher.connection.bind('unavailable', () => {
        console.log('⚠️ Echo service unavailable');
        window.broadcastConnectionStatus = 'unavailable';
        document.dispatchEvent(new CustomEvent('echo-unavailable'));
    });
    
} catch (error) {
    console.warn('⚠️ Failed to initialize Echo:', error);
    window.broadcastConnectionStatus = 'failed';
    window.Echo = null;
    document.dispatchEvent(new CustomEvent('echo-failed', { detail: error }));
}
