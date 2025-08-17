# Multi-stage Dockerfile for Laravel + Vue development and production

# Development stage
FROM php:8.2-fpm-alpine AS development

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite \
    supervisor \
    nginx

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath gd xml

# Copy PHP configuration for development
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code first
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Install Node dependencies  
RUN npm ci

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create SQLite database directory
RUN mkdir -p /var/www/html/database && \
    touch /var/www/html/database/database.sqlite && \
    chown www-data:www-data /var/www/html/database/database.sqlite

EXPOSE 9000

# Production stage
FROM development AS production

# Replace development PHP config with production config
COPY docker/php/production.ini /usr/local/etc/php/conf.d/local.ini

# Build frontend assets
RUN npm run build

# Remove development dependencies
RUN npm prune --production && \
    rm -rf node_modules && \
    npm ci --production

# Optimize Composer autoloader
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    composer dump-autoload --optimize

# Remove unnecessary files
RUN rm -rf .git tests

CMD ["php-fpm"]

# Development with hot reload
FROM development AS dev

# Install development dependencies
RUN composer install

# Expose ports for development
EXPOSE 8000 5173

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000 & npm run dev -- --host=0.0.0.0 --port=5173"]
