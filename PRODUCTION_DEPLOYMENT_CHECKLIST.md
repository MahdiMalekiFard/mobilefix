# Production Deployment Checklist for Laravel Reverb

This checklist covers everything you need to do when uploading your project to production with Reverb services.

## 🚀 Pre-Deployment Preparation

### 1. **Prepare Your Local Project**

#### **Update Environment Variables**
```bash
# In your local .env file, set production values
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Generate secure Reverb credentials
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

#### **Build Frontend Assets**
```bash
# Build production assets
npm run build

# Or if using development build
npm run dev
```

#### **Optimize Laravel**
```bash
# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📤 Upload Process

### 2. **Upload Project Files**

#### **Method 1: Git Deployment (Recommended)**
```bash
# On your production server
cd /var/www/your-project
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev
npm ci --production
npm run build
```

#### **Method 2: Manual Upload**
```bash
# Upload project files (exclude node_modules, vendor, .git)
rsync -av --exclude=node_modules --exclude=vendor --exclude=.git \
  /path/to/local/project/ user@server:/var/www/your-project/
```

### 3. **Upload Service Files**

```bash
# Upload systemd service files
scp laravel-reverb.service user@server:/home/user/
scp laravel-queue.service user@server:/home/user/
scp setup-reverb-services.sh user@server:/home/user/
scp manage-reverb-services.sh user@server:/home/user/
```

## 🔧 Server Setup

### 4. **SSH into Production Server**

```bash
ssh user@your-server
```

### 5. **Install System Dependencies**

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx php8.2-fpm php8.2-cli php8.2-mysql php8.2-redis \
  php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml \
  redis-server supervisor

# Install Node.js (if not already installed)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 6. **Set Up Reverb Services**

```bash
# Make scripts executable
chmod +x setup-reverb-services.sh
chmod +x manage-reverb-services.sh

# Run setup script
./setup-reverb-services.sh /var/www/your-project
```

### 7. **Configure Environment**

```bash
# Copy environment file
cp .env.example .env

# Edit environment file
nano .env
```

#### **Required .env Settings:**
```env
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=reverb

REVERB_APP_ID=your-unique-app-id
REVERB_APP_KEY=your-secure-app-key
REVERB_APP_SECRET=your-secure-app-secret
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=6001
REVERB_HOST=yourdomain.com
REVERB_PORT=443
REVERB_SCHEME=https

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 8. **Set Up Database**

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database (if needed)
php artisan db:seed --force
```

### 9. **Set Proper Permissions**

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/your-project

# Set permissions
sudo chmod -R 755 /var/www/your-project
sudo chmod -R 775 /var/www/your-project/storage
sudo chmod -R 775 /var/www/your-project/bootstrap/cache
```

### 10. **Configure Web Server (Nginx)**

#### **Create Nginx Configuration:**
```bash
sudo nano /etc/nginx/sites-available/your-project
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    # SSL Configuration
    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;

    # WebSocket Proxy for Reverb
    location /app/ {
        proxy_pass http://127.0.0.1:6001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        proxy_read_timeout 86400;
    }

    location /apps/ {
        proxy_pass http://127.0.0.1:6001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        proxy_read_timeout 86400;
    }

    # Your Laravel application
    root /var/www/your-project/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
}
```

#### **Enable Site:**
```bash
# Enable the site
sudo ln -s /etc/nginx/sites-available/your-project /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

## 🚀 Start Services

### 11. **Start All Services**

```bash
# Start Redis
sudo systemctl start redis
sudo systemctl enable redis

# Start Reverb services
./manage-reverb-services.sh start

# Or manually:
sudo systemctl start laravel-reverb
sudo systemctl start laravel-queue

# Enable auto-start on boot
sudo systemctl enable laravel-reverb
sudo systemctl enable laravel-queue
```

### 12. **Verify Everything is Working**

#### **Check Service Status:**
```bash
# Check all services
./manage-reverb-services.sh status

# Or manually:
sudo systemctl status laravel-reverb
sudo systemctl status laravel-queue
sudo systemctl status redis
sudo systemctl status nginx
```

#### **Check Logs:**
```bash
# View service logs
./manage-reverb-services.sh logs

# Or manually:
sudo journalctl -u laravel-reverb -f
sudo journalctl -u laravel-queue -f
```

#### **Test WebSocket Connection:**
```bash
# Test if Reverb is responding
curl -I http://localhost:6001

# Test WebSocket connection
wscat -c wss://yourdomain.com/app/your-reverb-app-key
```

#### **Test from Browser:**
1. Open your website in browser
2. Open Developer Tools (F12)
3. Check Console for Echo connection messages
4. Test real-time features (chat, notifications)

## 🔧 Post-Deployment Tasks

### 13. **Set Up Monitoring**

#### **Create Monitoring Script:**
```bash
nano /home/user/monitor-services.sh
```

```bash
#!/bin/bash
# Service monitoring script

echo "=== Service Status ==="
systemctl is-active laravel-reverb
systemctl is-active laravel-queue
systemctl is-active redis
systemctl is-active nginx

echo "=== Port Usage ==="
netstat -tlnp | grep :6001
netstat -tlnp | grep :80
netstat -tlnp | grep :443

echo "=== Memory Usage ==="
free -h

echo "=== Disk Usage ==="
df -h
```

```bash
chmod +x /home/user/monitor-services.sh
```

### 14. **Set Up Log Rotation**

```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/laravel-reverb
```

```ini
/var/www/your-project/storage/logs/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        sudo systemctl restart laravel-reverb
        sudo systemctl restart laravel-queue
    endscript
}
```

### 15. **Set Up Firewall**

```bash
# Allow HTTP and HTTPS
sudo ufw allow 80
sudo ufw allow 443

# Allow SSH (if needed)
sudo ufw allow 22

# Enable firewall
sudo ufw enable

# Note: Port 6001 should NOT be opened - it's only accessible via Nginx proxy
```

## 🧪 Testing Checklist

### 16. **Test All Features**

- [ ] Website loads correctly
- [ ] SSL certificate works
- [ ] Real-time chat works
- [ ] Messages appear instantly
- [ ] Typing indicators work
- [ ] File uploads work
- [ ] Admin panel works
- [ ] User panel works
- [ ] Database connections work
- [ ] Queue processing works

### 17. **Performance Testing**

```bash
# Test WebSocket connection
wscat -c wss://yourdomain.com/app/your-reverb-app-key

# Test queue processing
php artisan queue:work --once

# Check memory usage
free -h

# Check disk usage
df -h
```

## 🚨 Troubleshooting

### 18. **Common Issues & Solutions**

#### **Services Won't Start:**
```bash
# Check logs
sudo journalctl -u laravel-reverb -n 50
sudo journalctl -u laravel-queue -n 50

# Check permissions
ls -la /var/www/your-project/storage
ls -la /var/www/your-project/bootstrap/cache
```

#### **WebSocket Connection Fails:**
```bash
# Check Nginx configuration
sudo nginx -t

# Check if Reverb is running
sudo systemctl status laravel-reverb

# Check port usage
sudo netstat -tlnp | grep 6001
```

#### **Messages Not Broadcasting:**
```bash
# Check queue worker
sudo systemctl status laravel-queue

# Check Redis
redis-cli ping

# Test queue processing
php artisan queue:work --once
```

## 📋 Final Checklist

- [ ] Project uploaded successfully
- [ ] Environment variables configured
- [ ] Database set up and migrated
- [ ] Dependencies installed
- [ ] Permissions set correctly
- [ ] Nginx configured and running
- [ ] SSL certificate installed
- [ ] Redis running
- [ ] Reverb service running
- [ ] Queue service running
- [ ] All features tested
- [ ] Monitoring set up
- [ ] Log rotation configured
- [ ] Firewall configured
- [ ] Services enabled for auto-start

## 🎉 You're Done!

Your Laravel Reverb application should now be running in production with:
- ✅ Automatic service startup on boot
- ✅ Auto-restart on crashes
- ✅ Secure WebSocket connections
- ✅ Real-time broadcasting
- ✅ Proper logging and monitoring
- ✅ Production-ready configuration

The systemd services will keep your Reverb server running 24/7 with automatic recovery from any failures!
