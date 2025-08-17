#!/bin/bash

# Production Deployment Script for Laravel + Vue Starter Kit

set -e

echo "🚀 Production Deployment Script for Laravel + Vue Starter Kit"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
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

# Pre-deployment checks
print_status "Running pre-deployment checks..."

# Check if .env exists and has production settings
if [ ! -f .env ]; then
    print_error ".env file not found. Run ./setup-docker.sh first."
    exit 1
fi

if ! grep -q "APP_ENV=production" .env; then
    print_warning ".env file is not configured for production."
    read -p "Do you want to continue anyway? (y/N): " continue_anyway
    if [[ ! $continue_anyway =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Check for sensitive default values
print_status "Checking for default passwords..."
if grep -q "secure_production_password" .env; then
    print_error "Please update the default database password in .env file!"
    exit 1
fi

if grep -q "your_smtp" .env; then
    print_warning "SMTP settings appear to have default values. Please review email configuration."
fi

# Build production image
print_status "Building production Docker image..."
docker build --target production -t vue-starter-kit:production .

# Create production docker-compose if it doesn't exist
if [ ! -f docker-compose.prod.yml ]; then
    print_status "Creating production docker-compose configuration..."
    cat > docker-compose.prod.yml << 'EOF'
version: '3.8'

services:
  app:
    image: vue-starter-kit:production
    container_name: vue-starter-kit-app-prod
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./storage:/var/www/html/storage
      - ./.env:/var/www/html/.env
    depends_on:
      - mysql
      - redis
    networks:
      - laravel-prod

  mysql:
    image: mysql:8.0
    container_name: vue-starter-kit-mysql-prod
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:-root_secure_password}
    volumes:
      - mysql_prod_data:/var/lib/mysql
    networks:
      - laravel-prod

  redis:
    image: redis:7-alpine
    container_name: vue-starter-kit-redis-prod
    restart: unless-stopped
    command: redis-server --appendonly yes
    volumes:
      - redis_prod_data:/data
    networks:
      - laravel-prod

  nginx:
    image: nginx:alpine
    container_name: vue-starter-kit-nginx-prod
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./public:/var/www/html/public:ro
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf:ro
      - ./docker/nginx/ssl:/etc/nginx/ssl:ro
    depends_on:
      - app
    networks:
      - laravel-prod

volumes:
  mysql_prod_data:
  redis_prod_data:

networks:
  laravel-prod:
    driver: bridge
EOF
fi

# Deploy
print_status "Deploying to production..."
docker-compose -f docker-compose.prod.yml up -d

# Wait for services
print_status "Waiting for services to start..."
sleep 30

# Run production setup
CONTAINER_NAME="vue-starter-kit-app-prod"

print_status "Running production setup..."
docker exec $CONTAINER_NAME composer install --no-dev --optimize-autoloader --no-scripts

print_status "Running database migrations..."
docker exec $CONTAINER_NAME php artisan migrate --force

print_status "Optimizing for production..."
docker exec $CONTAINER_NAME php artisan config:cache
docker exec $CONTAINER_NAME php artisan route:cache
docker exec $CONTAINER_NAME php artisan view:cache
docker exec $CONTAINER_NAME php artisan optimize

print_status "Setting up storage permissions..."
docker exec $CONTAINER_NAME chown -R www-data:www-data /var/www/html/storage
docker exec $CONTAINER_NAME chmod -R 755 /var/www/html/storage

print_success "Production deployment completed!"

echo ""
echo "🎉 Production environment is ready!"
echo ""
echo "📋 Production Services:"
echo "   • Application: http://localhost"
echo "   • MySQL: Internal network only"
echo "   • Redis: Internal network only"
echo ""
echo "🔒 Security Reminders:"
echo "   • Configure SSL/TLS certificates"
echo "   • Update firewall rules"
echo "   • Review database security"
echo "   • Set up proper backup procedures"
echo "   • Configure monitoring and logging"
echo ""
echo "🛠️  Production Commands:"
echo "   • View logs: docker-compose -f docker-compose.prod.yml logs -f"
echo "   • Stop: docker-compose -f docker-compose.prod.yml down"
echo "   • Scale: docker-compose -f docker-compose.prod.yml up -d --scale app=2"
echo ""
echo "📊 Monitoring:"
echo "   • Check status: docker ps"
echo "   • Resource usage: docker stats"
echo "   • App logs: docker logs vue-starter-kit-app-prod"
