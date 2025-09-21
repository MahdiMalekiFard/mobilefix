/**
 * Broadcast Status Notification System
 * Shows users when real-time features are disabled
 */

class BroadcastStatusNotifier {
    constructor() {
        this.notificationShown = false;
        this.statusCheckInterval = null;
        this.init();
    }

    init() {
        // Listen for Echo connection events
        document.addEventListener('echo-connected', () => {
            this.hideNotification();
            this.stopStatusCheck();
        });

        document.addEventListener('echo-disconnected', () => {
            this.showNotification('Real-time updates temporarily unavailable. Messages will still be sent and received.');
        });

        document.addEventListener('echo-error', (event) => {
            this.showNotification('Real-time updates disabled. Messages will still be sent and received, but you may need to refresh to see new messages.');
        });

        document.addEventListener('echo-failed', (event) => {
            this.showNotification('Real-time updates disabled. Messages will still be sent and received, but you may need to refresh to see new messages.');
        });

        document.addEventListener('echo-unavailable', () => {
            this.showNotification('Real-time updates temporarily unavailable. Messages will still be sent and received.');
        });

        // Check connection status periodically
        this.startStatusCheck();
    }

    showNotification(message) {
        if (this.notificationShown) return;

        // Create notification element
        const notification = document.createElement('div');
        notification.id = 'broadcast-status-notification';
        notification.className = 'fixed top-4 right-4 z-50 max-w-sm bg-yellow-50 border border-yellow-200 rounded-lg shadow-lg p-4';
        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-yellow-800">Real-time Updates</p>
                    <p class="text-sm text-yellow-700 mt-1">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="text-yellow-400 hover:text-yellow-600">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);
        this.notificationShown = true;

        // Auto-hide after 10 seconds
        setTimeout(() => {
            this.hideNotification();
        }, 10000);
    }

    hideNotification() {
        const notification = document.getElementById('broadcast-status-notification');
        if (notification) {
            notification.remove();
            this.notificationShown = false;
        }
    }

    startStatusCheck() {
        // Check every 30 seconds if Echo is still connected
        this.statusCheckInterval = setInterval(() => {
            if (window.broadcastConnectionStatus === 'disconnected' || 
                window.broadcastConnectionStatus === 'error' || 
                window.broadcastConnectionStatus === 'unavailable' ||
                window.broadcastConnectionStatus === 'failed') {
                
                if (!this.notificationShown) {
                    this.showNotification('Real-time updates disabled. Messages will still be sent and received, but you may need to refresh to see new messages.');
                }
            }
        }, 30000);
    }

    stopStatusCheck() {
        if (this.statusCheckInterval) {
            clearInterval(this.statusCheckInterval);
            this.statusCheckInterval = null;
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new BroadcastStatusNotifier();
});

// Also initialize if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new BroadcastStatusNotifier();
    });
} else {
    new BroadcastStatusNotifier();
}
