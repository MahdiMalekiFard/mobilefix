# Reverb Real-Time Chat Troubleshooting Guide

## 🚨 Current Issue: Messages not appearing in real-time

### ✅ **Step 1: Update Your .env File**

Add these MISSING frontend variables to your `.env` file:

```env
# Broadcasting
BROADCAST_DRIVER=reverb

# Reverb Backend Config
REVERB_APP_ID=12345
REVERB_APP_KEY=reverb-key
REVERB_APP_SECRET=reverb-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

# CRITICAL: Frontend Variables (These are probably missing!)
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 🔄 **Step 2: Clear Cache and Rebuild**

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild frontend with new VITE variables
npm run build
```

### 🚀 **Step 3: Start All Required Services**

You need **3 terminals** running simultaneously:

**Terminal 1: Laravel Server**
```bash
php artisan serve
```

**Terminal 2: Reverb WebSocket Server**
```bash
php artisan reverb:start --host=127.0.0.1 --port=8080
```

**Terminal 3: Queue Worker**
```bash
php artisan queue:work --tries=3 --timeout=90
```

### 🧪 **Step 4: Test Connection**

**4.1 Check Reverb Server is Running:**
- Open http://127.0.0.1:8080 in browser
- You should see Reverb server info page

**4.2 Check Browser Console:**
- Open browser DevTools (F12)
- Look for WebSocket connection messages
- Should see connection to `ws://127.0.0.1:8080`

**4.3 Test Real-time Chat:**
- Admin chat: http://127.0.0.1:8000/admin/chat
- User chat: http://127.0.0.1:8000/user/chat
- Send message from admin → Should appear instantly in user chat

### 🔍 **Common Issues & Solutions**

#### **Issue 1: "VITE_* variables not found"**
**Solution:** Add VITE_ variables to .env and run `npm run build`

#### **Issue 2: "Connection refused on port 8080"**
**Solution:** Start Reverb server: `php artisan reverb:start`

#### **Issue 3: "Queue not processing"**
**Solution:** Start queue worker: `php artisan queue:work`

#### **Issue 4: "WebSocket connection failed"**
**Solution:** Check if port 8080 is blocked by firewall

#### **Issue 5: "Events not broadcasting"**
**Solution:** Check Laravel logs: `tail -f storage/logs/laravel.log`

### 🛠 **Debug Commands**

**Check Configuration:**
```bash
php artisan config:show broadcasting
```

**Test Queue:**
```bash
php artisan queue:failed
php artisan queue:retry all
```

**Check Reverb Status:**
```bash
php artisan reverb:restart
```

### 📋 **Verification Checklist**

- [ ] `.env` has all VITE_REVERB_* variables
- [ ] `npm run build` completed successfully
- [ ] Laravel server running on 8000
- [ ] Reverb server running on 8080
- [ ] Queue worker processing jobs
- [ ] Browser console shows WebSocket connection
- [ ] No errors in Laravel logs

### 🎯 **Expected Behavior**

When working correctly:
- ✅ Messages appear instantly without page refresh
- ✅ Typing indicators work in real-time
- ✅ Browser console shows successful WebSocket connection
- ✅ No errors in browser console or Laravel logs

### 📞 **Still Not Working?**

If following all steps above doesn't work:

1. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Browser Console** (F12) for errors

3. **Verify Port 8080 is accessible:**
   ```bash
   curl http://127.0.0.1:8080
   ```

4. **Try Alternative Port:**
   Change `REVERB_PORT=8081` in .env and restart services
