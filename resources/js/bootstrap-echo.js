/**
 * Laravel Echo setup for real-time broadcasting
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

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

window.Echo = new Echo(echoConfig);
