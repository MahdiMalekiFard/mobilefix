/**
 * Laravel Echo setup for real-time broadcasting with graceful fallback
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Check if Pusher is properly configured
const isPusherConfigured = () => {
    return import.meta.env.VITE_PUSHER_APP_KEY && 
           import.meta.env.VITE_PUSHER_APP_CLUSTER;
};

// Configuration based on environment - Pusher as primary
const echoConfig = {
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        },
    },
    authEndpoint: '/broadcasting/auth',
    // Add connection timeout and retry settings
    activityTimeout: 120000, // 2 minutes
    pongTimeout: 30000, // 30 seconds
    unavailableTimeout: 10000, // 10 seconds
    enabledTransports: ['ws', 'wss'],
    forceTLS: true,
};

// Fallback to Reverb if Pusher is not available
if (!isPusherConfigured()) {
    echoConfig.broadcaster = 'reverb';
    echoConfig.key = import.meta.env.VITE_REVERB_APP_KEY || 'reverb-key';
    echoConfig.wsHost = import.meta.env.VITE_REVERB_HOST || '127.0.0.1';
    echoConfig.wsPort = import.meta.env.VITE_REVERB_PORT || 8080;
    echoConfig.wssPort = import.meta.env.VITE_REVERB_PORT || 8080;
    echoConfig.forceTLS = (import.meta.env.VITE_REVERB_SCHEME || 'http') === 'https';
    echoConfig.enabledTransports = ['ws', 'wss'];
    delete echoConfig.cluster;
}

// Initialize Echo with error handling
console.log('🚀 Initializing Echo with config:', {
    broadcaster: echoConfig.broadcaster,
    key: echoConfig.key ? echoConfig.key.substring(0, 8) + '...' : 'NOT_SET',
    cluster: echoConfig.cluster,
    authEndpoint: echoConfig.authEndpoint
});

try {
    window.Echo = new Echo(echoConfig);
    
    // Add connection status tracking based on broadcaster type
    if (echoConfig.broadcaster === 'pusher') {
        // Pusher connection tracking
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('✅ Echo connected successfully via Pusher');
            window.broadcastConnectionStatus = 'connected';
            document.dispatchEvent(new CustomEvent('echo-connected'));
        });
        
        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            console.log('⚠️ Echo disconnected from Pusher');
            window.broadcastConnectionStatus = 'disconnected';
            document.dispatchEvent(new CustomEvent('echo-disconnected'));
        });
        
        window.Echo.connector.pusher.connection.bind('error', (error) => {
            console.log('❌ Echo connection error with Pusher:', error);
            window.broadcastConnectionStatus = 'error';
            document.dispatchEvent(new CustomEvent('echo-error', { detail: error }));
        });
        
        window.Echo.connector.pusher.connection.bind('unavailable', () => {
            console.log('⚠️ Echo service unavailable on Pusher');
            window.broadcastConnectionStatus = 'unavailable';
            document.dispatchEvent(new CustomEvent('echo-unavailable'));
        });
    } else if (echoConfig.broadcaster === 'reverb') {
        // Reverb connection tracking
        window.Echo.connector.socket.onopen = () => {
            console.log('✅ Echo connected successfully via Reverb');
            window.broadcastConnectionStatus = 'connected';
            document.dispatchEvent(new CustomEvent('echo-connected'));
        };
        
        window.Echo.connector.socket.onclose = () => {
            console.log('⚠️ Echo disconnected from Reverb');
            window.broadcastConnectionStatus = 'disconnected';
            document.dispatchEvent(new CustomEvent('echo-disconnected'));
        };
        
        window.Echo.connector.socket.onerror = (error) => {
            console.log('❌ Echo connection error with Reverb:', error);
            window.broadcastConnectionStatus = 'error';
            document.dispatchEvent(new CustomEvent('echo-error', { detail: error }));
        };
    }
    
} catch (error) {
    console.warn('⚠️ Failed to initialize Echo:', error);
    window.broadcastConnectionStatus = 'failed';
    window.Echo = null;
    document.dispatchEvent(new CustomEvent('echo-failed', { detail: error }));
}
