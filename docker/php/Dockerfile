# Stage 1: Composer dependencies
FROM composer:2 AS composer

FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    nginx \
    supervisor \
    && docker-php-ext-install pdo_pgsql zip

# Copy Composer from builder
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Install dependencies (no-dev for prod/Render)
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy configs
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/entrypoint.sh /entrypoint.sh
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN chmod +x /entrypoint.sh

# Expose port 80 for Render / Nginx
EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]

# Default command (Render uses this; local compose overrides)
CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisor.conf"]


# ############################
# # Stage 1: Composer vendor
# ############################
# FROM composer:2 AS vendor

# WORKDIR /app

# COPY composer.json composer.lock ./

# RUN composer install \
#     --no-dev \
#     --prefer-dist \
#     --no-interaction \
#     --no-progress \
#     --no-scripts \
#     --optimize-autoloader

# # Copy full app so artisan exists
# COPY . .

# # Now scripts are safe
# RUN composer dump-autoload --optimize \
#  && php artisan package:discover --ansi

# ############################
# # Stage 2: PHP runtime
# ############################
# FROM php:8.3-fpm-alpine

# RUN apk add --no-cache \
#         bash \
#         postgresql-libs \
#         libzip \
#     && apk add --no-cache --virtual .build-deps \
#         $PHPIZE_DEPS \
#         postgresql-dev \
#         libzip-dev \
#     && docker-php-ext-install \
#         pdo_pgsql \
#         zip \
#         opcache \
#     && apk del .build-deps

# WORKDIR /var/www

# # Copy app + vendor (already built)
# COPY --from=vendor /app /var/www

# RUN chown -R www-data:www-data storage bootstrap/cache

# USER www-data

# CMD ["php-fpm"]



