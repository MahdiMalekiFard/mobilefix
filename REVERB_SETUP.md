# Laravel Reverb Real-Time Chat Setup

This document explains how to set up and use Laravel Reverb for real-time chat functionality between users and admins.

## Environment Configuration

Add the following environment variables to your `.env` file:

```env
# Broadcasting Configuration
BROADCAST_DRIVER=reverb

# Reverb Configuration
REVERB_APP_ID=12345
REVERB_APP_KEY=reverb-key
REVERB_APP_SECRET=reverb-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

# Frontend Configuration
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## Setup Instructions

### 1. Install Dependencies
The required packages are already installed:
- `laravel/reverb` (Backend WebSocket server)
- `laravel-echo` (Frontend WebSocket client)
- `pusher-js` (Frontend transport layer)

### 2. Start the Reverb Server
Open a new terminal and run:

```bash
php artisan reverb:start
```

Or start with specific configuration:
```bash
php artisan reverb:start --host=127.0.0.1 --port=8080 --hostname=localhost
```

### 3. Start the Queue Worker
In another terminal, start the queue worker to process broadcasts:

```bash
php artisan queue:work
```

### 4. Build Frontend Assets
Compile the JavaScript assets with Echo configuration:

```bash
npm run build
```

Or for development:
```bash
npm run dev
```

## Features Implemented

### Real-Time Messaging
- ✅ Instant message delivery between users and admins
- ✅ File attachment broadcasting
- ✅ Automatic message synchronization
- ✅ Private channel authentication

### Typing Indicators
- ✅ Real-time typing notifications
- ✅ Start/stop typing events
- ✅ User identification in typing status

### Channel Authentication
- ✅ Private channels for conversation security
- ✅ Admin access to all conversations
- ✅ User access restricted to own conversations

## How It Works

### Message Broadcasting
When a message is sent, the system:
1. Creates the message in the database
2. Broadcasts a `MessageSent` event to the private channel
3. All authenticated participants receive the message instantly
4. UI updates automatically without page refresh

### Typing Indicators
When users type:
1. `startTyping()` method broadcasts typing status
2. `stopTyping()` method clears typing status
3. Real-time typing indicators appear for other participants

### Channel Structure
- Channel: `conversation.{conversationId}`
- Events: `MessageSent`, `UserTyping`
- Authentication: Based on user roles and conversation ownership

## Frontend Integration

The frontend automatically connects to Reverb and listens for events. No additional configuration needed.

### Echo Listeners (Already Configured)
```javascript
// User chat component
Echo.private(`conversation.${conversationId}`)
    .listen('MessageSent', (e) => {
        // Handle new message
    })
    .listen('UserTyping', (e) => {
        // Handle typing indicator
    });
```

## Testing

1. **Start all services:**
   ```bash
   # Terminal 1: Laravel server
   php artisan serve
   
   # Terminal 2: Reverb server
   php artisan reverb:start
   
   # Terminal 3: Queue worker
   php artisan queue:work
   
   # Terminal 4: Frontend (if developing)
   npm run dev
   ```

2. **Test real-time chat:**
   - Open user chat in one browser
   - Open admin chat in another browser/tab
   - Send messages and see instant delivery
   - Type messages and see typing indicators

## Troubleshooting

### Connection Issues
- Ensure Reverb server is running on correct port
- Check firewall settings for port 8080
- Verify environment variables are set correctly

### Authentication Issues
- Check Laravel session authentication
- Verify CSRF tokens are included
- Ensure user has proper permissions

### Broadcasting Issues
- Confirm queue worker is processing jobs
- Check Laravel logs for broadcast errors
- Verify event classes implement `ShouldBroadcast`

## Production Deployment

For production, consider:
- Using SSL/TLS (`wss://` instead of `ws://`)
- Setting up proper firewall rules
- Using a process manager like Supervisor for queue workers
- Implementing proper error handling and reconnection logic

### SSL Configuration
```env
REVERB_SCHEME=https
REVERB_PORT=443
VITE_REVERB_SCHEME=https
VITE_REVERB_PORT=443
```
