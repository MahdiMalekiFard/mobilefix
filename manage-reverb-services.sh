#!/bin/bash

# Laravel Reverb Services Management Script
# Usage: ./manage-reverb-services.sh [start|stop|restart|status|logs|enable|disable]

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

# Services array
SERVICES=("laravel-reverb" "laravel-queue")

# Function to execute command on all services
execute_on_services() {
    local command=$1
    local service_name=$2
    
    for service in "${SERVICES[@]}"; do
        if [ -n "$service_name" ] && [ "$service" != "$service_name" ]; then
            continue
        fi
        
        print_status "Executing '$command' on $service..."
        if sudo systemctl "$command" "$service"; then
            print_success "$service $command completed"
        else
            print_error "Failed to $command $service"
        fi
    done
}

# Function to show service status
show_status() {
    print_status "Service Status:"
    echo ""
    for service in "${SERVICES[@]}"; do
        echo "=== $service ==="
        sudo systemctl status "$service" --no-pager -l
        echo ""
    done
}

# Function to show logs
show_logs() {
    local service_name=$1
    local lines=${2:-50}
    
    if [ -n "$service_name" ]; then
        print_status "Showing last $lines lines of logs for $service_name:"
        sudo journalctl -u "$service_name" -n "$lines" --no-pager
    else
        print_status "Showing last $lines lines of logs for all services:"
        for service in "${SERVICES[@]}"; do
            echo "=== $service logs ==="
            sudo journalctl -u "$service" -n "$lines" --no-pager
            echo ""
        done
    fi
}

# Function to show help
show_help() {
    echo "Laravel Reverb Services Management Script"
    echo ""
    echo "Usage: $0 [COMMAND] [SERVICE]"
    echo ""
    echo "Commands:"
    echo "  start [service]     - Start all services or specific service"
    echo "  stop [service]      - Stop all services or specific service"
    echo "  restart [service]   - Restart all services or specific service"
    echo "  status             - Show status of all services"
    echo "  logs [service] [n] - Show logs (last n lines, default 50)"
    echo "  enable [service]   - Enable services to start on boot"
    echo "  disable [service]  - Disable services from starting on boot"
    echo "  help               - Show this help message"
    echo ""
    echo "Services:"
    echo "  laravel-reverb     - Reverb WebSocket server"
    echo "  laravel-queue      - Queue worker for broadcasting"
    echo ""
    echo "Examples:"
    echo "  $0 start                    # Start all services"
    echo "  $0 start laravel-reverb     # Start only Reverb service"
    echo "  $0 logs laravel-queue 100   # Show last 100 lines of queue logs"
    echo "  $0 restart                  # Restart all services"
}

# Main script logic
case "${1:-help}" in
    start)
        execute_on_services "start" "$2"
        ;;
    stop)
        execute_on_services "stop" "$2"
        ;;
    restart)
        execute_on_services "restart" "$2"
        ;;
    status)
        show_status
        ;;
    logs)
        show_logs "$2" "$3"
        ;;
    enable)
        execute_on_services "enable" "$2"
        ;;
    disable)
        execute_on_services "disable" "$2"
        ;;
    help|--help|-h)
        show_help
        ;;
    *)
        print_error "Unknown command: $1"
        echo ""
        show_help
        exit 1
        ;;
esac
