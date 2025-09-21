# How Systemd Services Work - Step by Step

## 🔄 Complete Workflow

```
┌─────────────────────────────────────────────────────────────────┐
│                        SERVER BOOT                             │
└─────────────────────┬───────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────┐
│                    SYSTEMD DAEMON                              │
│  • Reads service files from /etc/systemd/system/              │
│  • Manages service lifecycle                                   │
│  • Handles dependencies                                        │
└─────────────────────┬───────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────┐
│                   SERVICE STARTUP                              │
│                                                                 │
│  1. Check dependencies (network.target, redis.service)        │
│  2. Start services in correct order                            │
│  3. Run as specified user (www-data)                          │
│  4. Monitor process health                                     │
└─────────────────────┬───────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────┐
│                   RUNNING SERVICES                             │
│                                                                 │
│  ┌─────────────────┐    ┌─────────────────┐                   │
│  │ Laravel Reverb  │    │ Laravel Queue   │                   │
│  │ WebSocket Server│    │ Worker          │                   │
│  │ Port: 6001      │    │ Redis Queue     │                   │
│  │                 │    │                 │                   │
│  │ php artisan     │    │ php artisan     │                   │
│  │ reverb:start    │    │ queue:work      │                   │
│  └─────────────────┘    └─────────────────┘                   │
└─────────────────────┬───────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────┐
│                   MONITORING & LOGGING                         │
│                                                                 │
│  • journalctl logs all output                                 │
│  • Auto-restart on crash                                      │
│  • Resource monitoring                                        │
│  • Process health checks                                      │
└─────────────────────────────────────────────────────────────────┘
```

## 📋 Step-by-Step Process

### 1. **Server Boot Process**
```
Server starts → Systemd daemon loads → Reads service files → Starts services
```

### 2. **Service File Loading**
```bash
# Systemd reads these files:
/etc/systemd/system/laravel-reverb.service
/etc/systemd/system/laravel-queue.service

# And creates service definitions in memory
```

### 3. **Dependency Resolution**
```
network.target (network ready) → redis.service → laravel-queue.service
                              ↘ laravel-reverb.service
```

### 4. **Service Execution**
```bash
# Laravel Reverb Service
sudo -u www-data php /var/www/project/artisan reverb:start --host=0.0.0.0 --port=6001

# Laravel Queue Service  
sudo -u www-data php /var/www/project/artisan queue:work redis --sleep=3 --tries=3
```

### 5. **Process Monitoring**
```
Systemd monitors each process:
├── Process ID (PID)
├── Exit code
├── Resource usage
├── Output/Error streams
└── Restart conditions
```

## 🔧 Key Components Explained

### **Service File Structure**

#### **[Unit] Section**
```ini
[Unit]
Description=Laravel Reverb WebSocket Server  # Human readable name
After=network.target                         # Start after network is ready
Wants=network.target                         # Soft dependency
```
- **Purpose**: Defines service metadata and dependencies
- **Dependencies**: Ensures services start in correct order

#### **[Service] Section**
```ini
[Service]
Type=simple                    # Process type
User=www-data                  # Run as this user
Group=www-data                 # Run as this group
WorkingDirectory=/var/www/...  # Change to this directory
ExecStart=/usr/bin/php ...     # Command to execute
Restart=always                 # Restart policy
RestartSec=5                   # Wait time before restart
```
- **Purpose**: Defines how the service runs
- **Security**: Runs with limited privileges
- **Reliability**: Auto-restarts on failure

#### **[Install] Section**
```ini
[Install]
WantedBy=multi-user.target     # Enable for multi-user mode
```
- **Purpose**: Defines when service should be enabled
- **Boot**: Service starts automatically on boot

### **Process Management**

#### **Process Lifecycle**
```
1. START → Systemd forks new process
2. RUN   → Process runs continuously  
3. MONITOR → Systemd watches process health
4. RESTART → If process dies, restart after RestartSec
5. STOP  → Graceful shutdown on system stop
```

#### **Restart Policies**
```
always     → Always restart (default for our services)
on-failure → Restart only on failure
never      → Never restart
on-success → Restart only on success
```

### **Logging System**

#### **Journald Integration**
```bash
# All service output goes to systemd journal
sudo journalctl -u laravel-reverb    # View Reverb logs
sudo journalctl -u laravel-queue     # View Queue logs

# Real-time monitoring
sudo journalctl -u laravel-reverb -f # Follow logs live
```

#### **Log Levels**
```
StandardOutput=journal    # Normal output
StandardError=journal     # Error output  
SyslogIdentifier=laravel-reverb  # Log identifier
```

## 🚀 Service Management Commands

### **Basic Operations**
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
```

### **Enable/Disable (Boot Behavior)**
```bash
# Enable auto-start on boot
sudo systemctl enable laravel-reverb
sudo systemctl enable laravel-queue

# Disable auto-start on boot
sudo systemctl disable laravel-reverb
sudo systemctl disable laravel-queue
```

### **Status Checking**
```bash
# Check if running
sudo systemctl is-active laravel-reverb
sudo systemctl is-active laravel-queue

# Detailed status
sudo systemctl status laravel-reverb
sudo systemctl status laravel-queue
```

## 🔍 How It All Works Together

### **1. Server Boot Sequence**
```
BIOS/UEFI → Bootloader → Kernel → Systemd → Services
```

### **2. Service Startup Sequence**
```
1. Systemd reads service files
2. Resolves dependencies (network, redis)
3. Creates process with specified user/group
4. Executes the command in working directory
5. Monitors process health
6. Logs all output to journal
```

### **3. Runtime Behavior**
```
┌─────────────────┐    ┌─────────────────┐
│   Web Browser   │    │   Laravel App   │
│                 │    │                 │
│ Connects to     │    │ Sends message   │
│ WebSocket       │    │ to Queue        │
└─────────┬───────┘    └─────────┬───────┘
          │                      │
          │ WebSocket            │ Queue
          │ Connection           │ Job
          │                      │
          ▼                      ▼
┌─────────────────────────────────────────┐
│           Reverb Server                 │
│        (Port 6001)                     │
│                                         │
│  ┌─────────────────┐  ┌───────────────┐│
│  │ WebSocket       │  │ Queue Worker  ││
│  │ Connections     │  │ Processing    ││
│  │ Broadcasting    │  │ Messages      ││
│  └─────────────────┘  └───────────────┘│
└─────────────────────────────────────────┘
```

### **4. Failure Recovery**
```
Process crashes → Systemd detects → Waits RestartSec → Restarts process
```

## 🛡️ Security Features

### **Process Isolation**
```ini
User=www-data              # Limited user privileges
Group=www-data             # Limited group access
NoNewPrivileges=true       # Cannot gain privileges
PrivateTmp=true            # Private /tmp directory
ProtectSystem=strict       # Read-only system access
ProtectHome=true           # No home directory access
ReadWritePaths=/var/www/... # Only specific write access
```

### **Resource Limits**
```ini
LimitNOFILE=65536          # Max open files
LimitNPROC=4096           # Max processes
```

## 📊 Monitoring & Debugging

### **Health Checks**
```bash
# Check service status
systemctl is-active laravel-reverb
systemctl is-active laravel-queue

# Check if processes are running
ps aux | grep "artisan reverb:start"
ps aux | grep "artisan queue:work"

# Check port usage
netstat -tlnp | grep 6001
```

### **Log Analysis**
```bash
# View recent logs
journalctl -u laravel-reverb --since "1 hour ago"

# Search for errors
journalctl -u laravel-reverb | grep -i error

# Follow logs in real-time
journalctl -u laravel-reverb -f
```

This systemd approach provides enterprise-grade process management for your Laravel Reverb server!
