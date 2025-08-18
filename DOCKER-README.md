# Docker Development Environment - Quick Reference

## 🚀 Getting Started

Run the setup script to get started:
```bash
./setup-docker.sh
```

**Environment Options:**
1. **Local Development** - Full stack with MySQL, Redis, MailHog, phpMyAdmin
2. **Simple Development** - SQLite only for quick start
3. **Production** - Optimized production deployment

## 🌐 Available Services

### 1. Local Development Environment (Full Stack)
- **Laravel App**: http://localhost:8000
- **Vite Dev Server**: http://localhost:5173 (with HMR)
- **Nginx**: http://localhost:80
- **phpMyAdmin**: http://localhost:8080
- **MailHog Web UI**: http://localhost:8025
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

### 2. Simple Development Environment (SQLite)
- **Laravel App**: http://localhost:8000
- **Vite Dev Server**: http://localhost:5173 (with HMR)

### 3. Production Environment
- **Application**: http://localhost (via Nginx)
- **MySQL**: Internal network only
- **Redis**: Internal network only

## 📋 phpMyAdmin Access

When using the full stack environment, you can access phpMyAdmin at http://localhost:8080

**Login Credentials:**
- **Server**: mysql
- **Username**: laravel
- **Password**: password

**Root Access:**
- **Username**: root
- **Password**: root

## 🛠️ Useful Commands

### Environment Setup
```bash
# Interactive setup with environment selection
./setup-docker.sh

# Production deployment
./deploy-production.sh
```

### Container Management
```bash
# Local Development Environment
docker-compose up -d                    # Start
docker-compose down                     # Stop
docker-compose logs -f                  # View logs

# Simple Development Environment  
docker-compose -f docker-compose.dev.yml up -d     # Start
docker-compose -f docker-compose.dev.yml down      # Stop
docker-compose -f docker-compose.dev.yml logs -f   # View logs

# Production Environment
docker-compose -f docker-compose.prod.yml up -d    # Start
docker-compose -f docker-compose.prod.yml down     # Stop
docker-compose -f docker-compose.prod.yml logs -f  # View logs

# General commands
docker-compose up --build               # Rebuild containers
docker ps                              # View running containers
docker stats                           # Resource usage
```

### Application Commands
```bash
# Access app container
docker exec -it vue-starter-kit-app bash

# Run Artisan commands
docker exec vue-starter-kit-app php artisan migrate
docker exec vue-starter-kit-app php artisan make:controller ExampleController

# Run Composer commands
docker exec vue-starter-kit-app composer install
docker exec vue-starter-kit-app composer require package-name

# Run NPM commands
docker exec vue-starter-kit-app npm install
docker exec vue-starter-kit-app npm run build
```

### Database Management
```bash
# Access MySQL directly
docker exec -it vue-starter-kit-mysql mysql -u laravel -p

# Run migrations
docker exec vue-starter-kit-app php artisan migrate

# Seed database
docker exec vue-starter-kit-app php artisan db:seed

# Reset database
docker exec vue-starter-kit-app php artisan migrate:fresh --seed
```

## 🔧 Configuration Files

- `docker-compose.yml` - Local development environment (full stack)
- `docker-compose.dev.yml` - Simple development environment (SQLite)
- `docker-compose.prod.yml` - Production environment (auto-generated)
- `.env.docker` - Environment variables for local development
- `.env.dev` - Environment variables for simple development  
- `.env.production` - Environment variables for production
- `Dockerfile` - Multi-stage Docker build configuration
- `setup-docker.sh` - Interactive environment setup script
- `deploy-production.sh` - Production deployment script
- `docker/php/local.ini` - PHP configuration for development
- `docker/php/production.ini` - PHP configuration for production
- `docker/php/xdebug.ini` - Xdebug configuration for debugging
- `docker/nginx/default.conf` - Nginx server configuration

## 🐛 PHP Debugging with Xdebug

To enable Xdebug for debugging:

1. **Uncomment Xdebug in local.ini**:
   ```ini
   xdebug.mode = debug
   xdebug.start_with_request = yes
   xdebug.client_host = host.docker.internal
   xdebug.client_port = 9003
   ```

2. **Rebuild containers**:
   ```bash
   docker-compose down
   docker-compose up --build
   ```

3. **Configure your IDE**:
   - **VS Code**: Install PHP Debug extension
   - **PHPStorm**: Configure Xdebug with port 9003
   - **Other IDEs**: Set up remote debugging on port 9003

4. **Start debugging session** in your IDE and set breakpoints

## ⚙️ PHP Configuration Details

### Development Settings (`docker/php/local.ini`):
- Error reporting: Full display for debugging
- Memory limit: 512M
- Upload size: 64M
- Execution time: 60 seconds
- OPcache: Disabled for immediate code changes

### Production Settings (`docker/php/production.ini`):
- Error reporting: Logged only, not displayed
- Memory limit: 256M (optimized)
- Upload size: 32M
- Execution time: 30 seconds
- OPcache: Enabled for performance

## 📝 Development Workflow

1. **Start Development Environment**:
   ```bash
   ./setup-docker.sh
   ```

2. **Make Changes**: Edit your Vue components or Laravel code
   
3. **Hot Module Replacement**: Vite automatically reloads your frontend changes

4. **Database Management**: Use phpMyAdmin at http://localhost:8080 for database operations

5. **Email Testing**: Check emails in MailHog at http://localhost:8025

6. **Debugging**: Access logs with `docker-compose logs -f`

## 🚨 Troubleshooting

### Port Conflicts
If you get port conflicts, you can modify the ports in `docker-compose.yml`:
```yaml
ports:
  - "8001:8000"  # Change 8000 to 8001
```

### Container Won't Start
```bash
# Check container status
docker ps -a

# View container logs
docker-compose logs [service-name]

# Rebuild containers
docker-compose down
docker-compose up --build
```

### Database Connection Issues
1. Ensure MySQL container is running: `docker ps`
2. Check database credentials in `.env`
3. Wait for MySQL to fully initialize (can take 30-60 seconds)

### Permission Issues
```bash
# Fix file permissions
sudo chown -R $USER:$USER .
chmod -R 755 storage bootstrap/cache
```
