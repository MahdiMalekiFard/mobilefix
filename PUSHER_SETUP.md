# Pusher Setup for Live Chat

This project has been configured to use Pusher instead of Reverb for real-time live chat functionality between users and admins.

## Required Environment Variables

Add the following variables to your `.env` file:

```env
# Broadcasting Configuration
BROADCAST_DRIVER=pusher

# Pusher Configuration
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-app-key
PUSHER_APP_SECRET=your-pusher-app-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

# Vite Environment Variables for Pusher
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## Getting Pusher Credentials

1. Go to [Pusher Dashboard](https://dashboard.pusher.com/)
2. Create a new app or use an existing one
3. Go to the "App Keys" tab
4. Copy the following values:
   - **App ID** → `PUSHER_APP_ID`
   - **Key** → `PUSHER_APP_KEY`
   - **Secret** → `PUSHER_APP_SECRET`
   - **Cluster** → `PUSHER_APP_CLUSTER`

## Configuration Changes Made

1. **bootstrap-echo.js**: Updated to use Pusher as primary broadcaster with Reverb as fallback
2. **BroadcastHelper.php**: Added Pusher configuration validation
3. **broadcasting.php**: Set Pusher as default broadcast driver
4. **Chat listeners**: Already configured to work with both Pusher and Reverb

## Testing the Setup

1. Set up your Pusher credentials in `.env`
2. Run `npm run build` to compile the assets
3. Start your Laravel application
4. Test the live chat functionality between user and admin interfaces

## Fallback to Reverb

If Pusher credentials are not provided, the system will automatically fall back to Reverb configuration. Make sure to keep your Reverb environment variables as backup:

```env
# Reverb Configuration (fallback)
REVERB_APP_ID=your-reverb-app-id
REVERB_APP_KEY=your-reverb-app-key
REVERB_APP_SECRET=your-reverb-app-secret
REVERB_HOST="127.0.0.1"
REVERB_PORT=8080
REVERB_SCHEME=http

# Vite Environment Variables for Reverb (fallback)
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## Benefits of Using Pusher

- **Reliability**: Pusher provides a more stable and reliable real-time service
- **Scalability**: Better handling of concurrent connections
- **Global CDN**: Faster message delivery worldwide
- **No server maintenance**: No need to run Reverb server
- **Better error handling**: More robust connection management
