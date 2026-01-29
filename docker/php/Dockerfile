# Stage 1: Composer dependencies
FROM composer:2 AS composer-deps

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist --ignore-platform-reqs

# Stage 2: Final image with PHP and nginx
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    postgresql-dev \
    libzip-dev \
    libzip \
    postgresql-libs \
    bash

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql zip pcntl && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    apk del .build-deps

# Install Node.js for Scribe documentation generation (if needed)
RUN apk add --no-cache nodejs npm

WORKDIR /var/www

# Copy Composer vendor from stage 1
COPY --from=composer-deps /app/vendor /var/www/vendor

# Copy application code
COPY . .

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Generate Scribe documentation during build
RUN php artisan scribe:generate

# Copy configuration files
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/php/entrypoint.sh /entrypoint.sh
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN chmod +x /entrypoint.sh

# Fix nginx configuration - ensure directory exists
RUN mkdir -p /run/nginx && \
    chown -R www-data:www-data /run/nginx

# For Render.com, they need to listen on PORT environment variable
EXPOSE 10000

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisor.conf"]




# # Stage 1: Composer dependencies
# FROM composer:2 AS composer-deps

# WORKDIR /app

# COPY composer.json composer.lock ./

# RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist --ignore-platform-reqs

# # Stage 2: Final image with PHP and nginx
# FROM php:8.3-fpm-alpine

# # Install system dependencies
# RUN apk update && apk add --no-cache \
#     nginx \
#     supervisor \
#     postgresql-dev \
#     libzip-dev \
#     libzip \
#     postgresql-libs \
#     bash

# # Install PHP extensions
# RUN docker-php-ext-install pdo_pgsql zip pcntl && \
#     apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
#     pecl install redis && \
#     docker-php-ext-enable redis && \
#     apk del .build-deps

# WORKDIR /var/www

# # Copy Composer vendor from stage 1
# COPY --from=composer-deps /app/vendor /var/www/vendor

# # Copy application code
# COPY . .

# # Fix permissions
# RUN chown -R www-data:www-data storage bootstrap/cache \
#     && chmod -R 775 storage bootstrap/cache

# # Copy configuration files
# COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
# COPY docker/php/entrypoint.sh /entrypoint.sh
# COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# RUN chmod +x /entrypoint.sh

# # Fix nginx configuration - ensure directory exists
# RUN mkdir -p /run/nginx && \
#     chown -R www-data:www-data /run/nginx

# EXPOSE 80

# ENTRYPOINT ["/entrypoint.sh"]
# CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisor.conf"]



