#!/bin/bash

# Docker Development Environment Setup Script for Laravel + Vue Starter Kit

set -e

echo "🚀 Setting up Docker development environment for Laravel + Vue Starter Kit..."

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

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Ask user for environment type
print_status "Select your environment setup:"
echo "1) Local Development Environment (Full stack with MySQL, Redis, MailHog, phpMyAdmin)"
echo "2) Simple Development Environment (SQLite only - quick start)"
echo "3) Production Environment (Optimized for production deployment)"
read -p "Enter your choice (1, 2, or 3): " env_choice

# Create .env file based on choice
if [ ! -f .env ]; then
    print_status "Creating .env file..."
    
    case $env_choice in
        1)
            cp .env.docker .env
            print_success ".env file created with full stack development configuration"
            ;;
        2)
            cp .env.dev .env
            print_success ".env file created with simple SQLite development configuration"
            ;;
        3)
            cp .env.production .env
            print_success ".env file created with production configuration"
            print_warning "⚠️  IMPORTANT: Update database passwords and SMTP settings in .env file!"
            ;;
        *)
            print_warning "Invalid choice. Using simple SQLite development configuration."
            cp .env.dev .env
            env_choice=2
            ;;
    esac
else
    print_warning ".env file already exists. Skipping creation."
    # Try to detect environment type from existing .env
    if grep -q "APP_ENV=production" .env; then
        env_choice=3
        print_status "Detected existing production environment configuration"
    elif grep -q "DB_CONNECTION=mysql" .env; then
        env_choice=1
        print_status "Detected existing full stack development configuration"
    else
        env_choice=2
        print_status "Detected existing simple development configuration"
    fi
fi

# Generate application key
print_status "Generating application key..."
if [ -f .env ]; then
    # Check if APP_KEY is empty
    if grep -q "APP_KEY=$" .env; then
        # Generate a random key
        APP_KEY=$(openssl rand -base64 32)
        sed -i "s/APP_KEY=$/APP_KEY=base64:$APP_KEY/" .env
        print_success "Application key generated"
    else
        print_warning "Application key already exists"
    fi
fi

# Create database directory for SQLite
print_status "Setting up database directory..."
mkdir -p database
touch database/database.sqlite
chmod 664 database/database.sqlite

# Build and start containers based on environment choice
print_status "Building Docker containers..."

case $env_choice in
    1)
        print_status "Starting local development environment with full stack..."
        docker-compose up --build -d
        CONTAINER_NAME="vue-starter-kit-app"
        ;;
    2)
        print_status "Starting simple development environment..."
        docker-compose -f docker-compose.dev.yml up --build -d
        CONTAINER_NAME="vue-starter-kit-app-dev"
        ;;
    3)
        print_status "Building production environment..."
        # Build production image
        docker build --target production -t vue-starter-kit:production .
        
        # Create production docker-compose file on the fly
        cat > docker-compose.prod.yml << EOF
version: '3.8'

services:
  app:
    image: vue-starter-kit:production
    container_name: vue-starter-kit-app-prod
    ports:
      - "8000:8000"
    volumes:
      - ./storage:/var/www/html/storage
      - ./.env:/var/www/html/.env
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    depends_on:
      - mysql
      - redis
    networks:
      - laravel-prod

  mysql:
    image: mysql:8.0
    container_name: vue-starter-kit-mysql-prod
    environment:
      MYSQL_DATABASE: laravel
      MYSQL_USER: laravel
      MYSQL_PASSWORD: \${DB_PASSWORD:-secure_password}
      MYSQL_ROOT_PASSWORD: \${DB_ROOT_PASSWORD:-root_secure_password}
    volumes:
      - mysql_prod_data:/var/lib/mysql
    networks:
      - laravel-prod

  redis:
    image: redis:7-alpine
    container_name: vue-starter-kit-redis-prod
    networks:
      - laravel-prod

  nginx:
    image: nginx:alpine
    container_name: vue-starter-kit-nginx-prod
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - laravel-prod

volumes:
  mysql_prod_data:

networks:
  laravel-prod:
    driver: bridge
EOF
        
        docker-compose -f docker-compose.prod.yml up -d
        CONTAINER_NAME="vue-starter-kit-app-prod"
        ;;
esac

# Wait for containers to be ready
print_status "Waiting for containers to be ready..."
if [ "$env_choice" == "3" ]; then
    sleep 20  # Production needs more time
else
    sleep 10
fi

# Run Laravel setup commands
print_status "Running Laravel setup commands..."

# Check if container is running
if docker ps | grep -q $CONTAINER_NAME; then
    print_status "Installing Composer dependencies..."
    if [ "$env_choice" == "3" ]; then
        docker exec $CONTAINER_NAME composer install --no-dev --optimize-autoloader
    else
        docker exec $CONTAINER_NAME composer install
    fi
    
    if [ "$env_choice" != "3" ]; then
        print_status "Installing NPM dependencies..."
        docker exec $CONTAINER_NAME npm install
    fi
    
    if [ "$env_choice" == "1" ] || [ "$env_choice" == "3" ]; then
        print_status "Waiting for MySQL to be ready..."
        sleep 15
        
        print_status "Running database migrations..."
        docker exec $CONTAINER_NAME php artisan migrate --force
        
        if [ "$env_choice" == "1" ]; then
            print_status "Seeding database for development..."
            docker exec $CONTAINER_NAME php artisan db:seed --force
        fi
    fi
    
    print_status "Clearing application cache..."
    docker exec $CONTAINER_NAME php artisan config:clear
    docker exec $CONTAINER_NAME php artisan cache:clear
    docker exec $CONTAINER_NAME php artisan view:clear
    
    if [ "$env_choice" == "3" ]; then
        print_status "Optimizing for production..."
        docker exec $CONTAINER_NAME php artisan config:cache
        docker exec $CONTAINER_NAME php artisan route:cache
        docker exec $CONTAINER_NAME php artisan view:cache
    fi
    
    print_success "Setup completed successfully!"
    
    echo ""
    echo "🎉 Your Laravel + Vue environment is ready!"
    echo ""
    
    case $env_choice in
        1)
            echo "📋 Local Development Environment - Available services:"
            echo "   • Laravel App: http://localhost:8000"
            echo "   • Vite Dev Server: http://localhost:5173 (with HMR)"
            echo "   • MySQL: localhost:3306"
            echo "   • phpMyAdmin: http://localhost:8080"
            echo "   • Redis: localhost:6379"
            echo "   • MailHog: http://localhost:8025"
            echo "   • Nginx: http://localhost:80"
            echo ""
            echo "🛠️  Development commands:"
            echo "   • Stop: docker-compose down"
            echo "   • Restart: docker-compose restart"
            echo "   • Logs: docker-compose logs -f"
            echo "   • Shell: docker exec -it $CONTAINER_NAME bash"
            ;;
        2)
            echo "📋 Simple Development Environment - Available services:"
            echo "   • Laravel App: http://localhost:8000"
            echo "   • Vite Dev Server: http://localhost:5173 (with HMR)"
            echo ""
            echo "🛠️  Development commands:"
            echo "   • Stop: docker-compose -f docker-compose.dev.yml down"
            echo "   • Restart: docker-compose -f docker-compose.dev.yml restart"
            echo "   • Logs: docker-compose -f docker-compose.dev.yml logs -f"
            echo "   • Shell: docker exec -it $CONTAINER_NAME bash"
            ;;
        3)
            echo "📋 Production Environment - Available services:"
            echo "   • Laravel App: http://localhost:8000"
            echo "   • Nginx: http://localhost:80"
            echo "   • MySQL: localhost:3306 (production)"
            echo "   • Redis: localhost:6379 (production)"
            echo ""
            echo "🛠️  Production commands:"
            echo "   • Stop: docker-compose -f docker-compose.prod.yml down"
            echo "   • Restart: docker-compose -f docker-compose.prod.yml restart"
            echo "   • Logs: docker-compose -f docker-compose.prod.yml logs -f"
            echo "   • Shell: docker exec -it $CONTAINER_NAME bash"
            echo ""
            echo "🔒 Security Notes:"
            echo "   • Change default database passwords in production!"
            echo "   • Configure proper SSL certificates for HTTPS"
            echo "   • Review and update security settings"
            ;;
    esac
    
    echo ""
    echo "💡 Tips:"
    if [ "$env_choice" != "3" ]; then
        echo "   • The Vite dev server provides hot module replacement (HMR)"
        echo "   • Any changes to Vue files will be reflected immediately"
    fi
    echo "   • Check DOCKER-README.md for detailed documentation"
    echo "   • Use 'docker ps' to see running containers"
    
else
    print_error "Container failed to start. Please check the logs with: docker-compose logs"
    exit 1
fi
