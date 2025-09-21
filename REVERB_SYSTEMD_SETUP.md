# Laravel Reverb Systemd Services Setup

This guide shows how to set up Laravel Reverb as systemd services (system jobs) that automatically start when the server boots and keep running continuously.

## 📋 What We're Creating

1. **`laravel-reverb.service`** - Runs the Reverb WebSocket server
2. **`laravel-queue.service`** - Runs the queue worker for broadcasting
3. **Setup script** - Automates the installation
4. **Management script** - Easy service control

## 🚀 Quick Setup

### Step 1: Upload Files to Server

Upload these files to your server:
```bash
scp setup-reverb-services.sh user@your-server:/home/user/
scp manage-reverb-services.sh user@your-server:/home/user/
scp laravel-reverb.service user@your-server:/home/user/
scp laravel-queue.service user@your-server:/home/user/
```

### Step 2: Run Setup Script

```bash
ssh user@your-server
chmod +x setup-reverb-services.sh
./setup-reverb-services.sh /var/www/your-laravel-project
```

### Step 3: Start Services

```bash
chmod +x manage-reverb-services.sh
./manage-reverb-services.sh start
```

## 🔧 Manual Setup (Alternative)

If you prefer to set up manually:

### 1. Create Systemd Service Files

**Laravel Reverb Service:**
```bash
sudo nano /etc/systemd/system/laravel-reverb.service
```

```ini
[Unit]
Description=Laravel Reverb WebSocket Server
After=network.target
Wants=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/your-project
ExecStart=/usr/bin/php artisan reverb:start --host=0.0.0.0 --port=6001
ExecReload=/bin/kill -HUP $MAINPID
Restart=always
RestartSec=5
StandardOutput=journal
StandardError=journal
SyslogIdentifier=laravel-reverb

[Install]
WantedBy=multi-user.target
```

**Laravel Queue Service:**
```bash
sudo nano /etc/systemd/system/laravel-queue.service
```

```ini
[Unit]
Description=Laravel Queue Worker for Broadcasting
After=network.target redis.service
Wants=network.target redis.service

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/your-project
ExecStart=/usr/bin/php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
ExecReload=/bin/kill -HUP $MAINPID
Restart=always
RestartSec=5
StandardOutput=journal
StandardError=journal
SyslogIdentifier=laravel-queue

[Install]
WantedBy=multi-user.target
```

### 2. Enable and Start Services

```bash
# Reload systemd daemon
sudo systemctl daemon-reload

# Enable services to start on boot
sudo systemctl enable laravel-reverb
sudo systemctl enable laravel-queue

# Start services now
sudo systemctl start laravel-reverb
sudo systemctl start laravel-queue
```

## 🎛️ Service Management

### Using the Management Script

```bash
# Start all services
./manage-reverb-services.sh start

# Start specific service
./manage-reverb-services.sh start laravel-reverb

# Stop all services
./manage-reverb-services.sh stop

# Restart all services
./manage-reverb-services.sh restart

# Check status
./manage-reverb-services.sh status

# View logs
./manage-reverb-services.sh logs
./manage-reverb-services.sh logs laravel-reverb 100

# Enable auto-start on boot
./manage-reverb-services.sh enable

# Disable auto-start on boot
./manage-reverb-services.sh disable
```

### Using Systemctl Directly

```bash
# Start services
sudo systemctl start laravel-reverb
sudo systemctl start laravel-queue

# Stop services
sudo systemctl stop laravel-reverb
sudo systemctl stop laravel-queue

# Restart services
sudo systemctl restart laravel-reverb
sudo systemctl restart laravel-queue

# Check status
sudo systemctl status laravel-reverb
sudo systemctl status laravel-queue

# View logs
sudo journalctl -u laravel-reverb -f
sudo journalctl -u laravel-queue -f

# Enable auto-start
sudo systemctl enable laravel-reverb
sudo systemctl enable laravel-queue

# Disable auto-start
sudo systemctl disable laravel-reverb
sudo systemctl disable laravel-queue
```

## 📊 Monitoring Services

### Check Service Status
```bash
# Quick status check
systemctl is-active laravel-reverb
systemctl is-active laravel-queue

# Detailed status
systemctl status laravel-reverb --no-pager
systemctl status laravel-queue --no-pager
```

### View Logs
```bash
# Real-time logs
sudo journalctl -u laravel-reverb -f
sudo journalctl -u laravel-queue -f

# Last 100 lines
sudo journalctl -u laravel-reverb -n 100
sudo journalctl -u laravel-queue -n 100

# Logs from today
sudo journalctl -u laravel-reverb --since today
sudo journalctl -u laravel-queue --since today
```

### Monitor Resource Usage
```bash
# Check if services are running
ps aux | grep "artisan reverb:start"
ps aux | grep "artisan queue:work"

# Check port usage
sudo netstat -tlnp | grep 6001
sudo ss -tlnp | grep 6001
```

## 🔧 Configuration

### Environment Variables

Make sure your `.env` file has these settings:

```env
# Broadcasting
BROADCAST_DRIVER=reverb

# Reverb Configuration
REVERB_APP_ID=your-unique-app-id
REVERB_APP_KEY=your-secure-app-key
REVERB_APP_SECRET=your-secure-app-secret

# Reverb Server Settings
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=6001
REVERB_HOST=yourdomain.com
REVERB_PORT=443
REVERB_SCHEME=https

# Frontend Variables
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Queue Configuration
QUEUE_CONNECTION=redis
```

### Service Configuration

To modify service settings, edit the service files:

```bash
sudo systemctl edit laravel-reverb
sudo systemctl edit laravel-queue
```

This opens an override file where you can add custom settings.

## 🚨 Troubleshooting

### Service Won't Start
```bash
# Check service status
sudo systemctl status laravel-reverb

# Check logs for errors
sudo journalctl -u laravel-reverb -n 50

# Check if port is in use
sudo netstat -tlnp | grep 6001

# Kill process using port
sudo fuser -k 6001/tcp
```

### Service Keeps Restarting
```bash
# Check logs for restart reason
sudo journalctl -u laravel-reverb -n 100

# Check Laravel configuration
php artisan config:cache
php artisan route:cache
```

### Permission Issues
```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/your-project
sudo chmod -R 775 /var/www/your-project/storage
sudo chmod -R 775 /var/www/your-project/bootstrap/cache
```

### Redis Connection Issues
```bash
# Check Redis status
sudo systemctl status redis

# Test Redis connection
redis-cli ping

# Check Laravel Redis config
php artisan tinker
>>> Redis::ping()
```

## 🔄 Maintenance

### Update Application
```bash
# Stop services
sudo systemctl stop laravel-reverb laravel-queue

# Update code
git pull origin main
composer install --optimize-autoloader --no-dev
npm run build

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl start laravel-reverb laravel-queue
```

### Backup Configuration
```bash
# Backup service files
sudo cp /etc/systemd/system/laravel-reverb.service ~/backup/
sudo cp /etc/systemd/system/laravel-queue.service ~/backup/
```

## 📈 Benefits of Systemd Services

1. **Automatic Startup** - Services start when server boots
2. **Auto-Restart** - Services restart if they crash
3. **Process Management** - Proper process lifecycle management
4. **Logging** - Integrated logging with journald
5. **Resource Limits** - Can set memory and CPU limits
6. **Security** - Can run with restricted permissions
7. **Monitoring** - Easy status checking and monitoring

## 🆚 Systemd vs Cron vs Supervisor

| Feature | Systemd | Cron | Supervisor |
|---------|---------|------|------------|
| Auto-start on boot | ✅ | ❌ | ✅ |
| Process management | ✅ | ❌ | ✅ |
| Auto-restart | ✅ | ❌ | ✅ |
| Resource limits | ✅ | ❌ | ✅ |
| Security features | ✅ | ❌ | ✅ |
| Built-in logging | ✅ | ❌ | ✅ |
| System integration | ✅ | ✅ | ❌ |

**Recommendation:** Use systemd services for production Laravel Reverb deployment.

This setup provides robust, production-ready service management for your Laravel Reverb WebSocket server!
