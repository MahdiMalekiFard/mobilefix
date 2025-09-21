#!/bin/bash

# Laravel Reverb Systemd Services Setup Script
# Usage: ./setup-reverb-services.sh /path/to/your/laravel/project

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root. Please run as a user with sudo privileges."
   exit 1
fi

# Get project path
PROJECT_PATH=${1:-"/var/www/html"}

if [ ! -d "$PROJECT_PATH" ]; then
    print_error "Project path does not exist: $PROJECT_PATH"
    exit 1
fi

if [ ! -f "$PROJECT_PATH/artisan" ]; then
    print_error "Laravel artisan file not found in: $PROJECT_PATH"
    exit 1
fi

print_status "Setting up Laravel Reverb systemd services..."
print_status "Project path: $PROJECT_PATH"

# Check if systemd is available
if ! command -v systemctl &> /dev/null; then
    print_error "systemd is not available on this system"
    exit 1
fi

# Generate secure Reverb credentials if they don't exist
print_status "Checking Reverb credentials..."

if ! grep -q "REVERB_APP_ID" "$PROJECT_PATH/.env"; then
    print_warning "Reverb credentials not found in .env file"
    print_status "Generating secure Reverb credentials..."
    
    REVERB_APP_ID=$(openssl rand -hex 10)
    REVERB_APP_KEY=$(openssl rand -hex 32)
    REVERB_APP_SECRET=$(openssl rand -hex 32)
    
    # Add to .env file
    cat >> "$PROJECT_PATH/.env" << EOF

# Reverb Configuration
BROADCAST_DRIVER=reverb
REVERB_APP_ID=$REVERB_APP_ID
REVERB_APP_KEY=$REVERB_APP_KEY
REVERB_APP_SECRET=$REVERB_APP_SECRET
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=6001
REVERB_HOST=localhost
REVERB_PORT=6001
REVERB_SCHEME=http

# Frontend Variables
VITE_REVERB_APP_KEY="$REVERB_APP_KEY"
VITE_REVERB_HOST="localhost"
VITE_REVERB_PORT="6001"
VITE_REVERB_SCHEME="http"

# Queue Configuration
QUEUE_CONNECTION=redis
EOF
    
    print_success "Generated and added Reverb credentials to .env file"
    print_warning "IMPORTANT: Update REVERB_HOST and REVERB_SCHEME with your actual domain and HTTPS settings"
else
    print_success "Reverb credentials found in .env file"
fi

# Create systemd service files
print_status "Creating systemd service files..."

# Create Laravel Reverb service
sudo tee /etc/systemd/system/laravel-reverb.service > /dev/null <<EOF
[Unit]
Description=Laravel Reverb WebSocket Server
After=network.target
Wants=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=$PROJECT_PATH
ExecStart=/usr/bin/php artisan reverb:start --host=0.0.0.0 --port=6001
ExecReload=/bin/kill -HUP \$MAINPID
Restart=always
RestartSec=5
StandardOutput=journal
StandardError=journal
SyslogIdentifier=laravel-reverb

# Security settings
NoNewPrivileges=true
PrivateTmp=true
ProtectSystem=strict
ProtectHome=true
ReadWritePaths=$PROJECT_PATH/storage

# Resource limits
LimitNOFILE=65536
LimitNPROC=4096

# Environment variables
Environment=APP_ENV=production
Environment=APP_DEBUG=false

[Install]
WantedBy=multi-user.target
EOF

# Create Laravel Queue service
sudo tee /etc/systemd/system/laravel-queue.service > /dev/null <<EOF
[Unit]
Description=Laravel Queue Worker for Broadcasting
After=network.target redis.service
Wants=network.target redis.service

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=$PROJECT_PATH
ExecStart=/usr/bin/php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --timeout=60
ExecReload=/bin/kill -HUP \$MAINPID
Restart=always
RestartSec=5
StandardOutput=journal
StandardError=journal
SyslogIdentifier=laravel-queue

# Security settings
NoNewPrivileges=true
PrivateTmp=true
ProtectSystem=strict
ProtectHome=true
ReadWritePaths=$PROJECT_PATH/storage

# Resource limits
LimitNOFILE=65536
LimitNPROC=4096

# Environment variables
Environment=APP_ENV=production
Environment=APP_DEBUG=false

[Install]
WantedBy=multi-user.target
EOF

print_success "Created systemd service files"

# Set proper permissions
print_status "Setting proper permissions..."
sudo chown -R www-data:www-data "$PROJECT_PATH"
sudo chmod -R 755 "$PROJECT_PATH"
sudo chmod -R 775 "$PROJECT_PATH/storage"
sudo chmod -R 775 "$PROJECT_PATH/bootstrap/cache"

# Reload systemd and enable services
print_status "Reloading systemd daemon..."
sudo systemctl daemon-reload

print_status "Enabling services to start on boot..."
sudo systemctl enable laravel-reverb.service
sudo systemctl enable laravel-queue.service

# Check if Redis is available
if command -v redis-cli &> /dev/null; then
    if redis-cli ping &> /dev/null; then
        print_success "Redis is running"
    else
        print_warning "Redis is installed but not running. Please start Redis:"
        echo "  sudo systemctl start redis"
        echo "  sudo systemctl enable redis"
    fi
else
    print_warning "Redis is not installed. Please install Redis:"
    echo "  sudo apt install redis-server  # Ubuntu/Debian"
    echo "  sudo yum install redis         # CentOS/RHEL"
fi

print_success "Systemd services setup completed!"

print_warning "IMPORTANT: Next steps to complete the setup:"
echo ""
echo "1. Update your .env file with the correct domain and HTTPS settings:"
echo "   REVERB_HOST=yourdomain.com"
echo "   REVERB_PORT=443"
echo "   REVERB_SCHEME=https"
echo ""
echo "2. Start the services:"
echo "   sudo systemctl start laravel-reverb"
echo "   sudo systemctl start laravel-queue"
echo ""
echo "3. Check service status:"
echo "   sudo systemctl status laravel-reverb"
echo "   sudo systemctl status laravel-queue"
echo ""
echo "4. View service logs:"
echo "   sudo journalctl -u laravel-reverb -f"
echo "   sudo journalctl -u laravel-queue -f"
echo ""
echo "5. Configure your web server (Nginx/Apache) to proxy WebSocket connections"
echo ""
print_success "Laravel Reverb systemd services are ready!"
