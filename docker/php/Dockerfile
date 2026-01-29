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

WORKDIR /var/www

# Copy Composer vendor from stage 1
COPY --from=composer-deps /app/vendor /var/www/vendor

# Copy application code
COPY . .

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy configuration files
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/php/entrypoint.sh /entrypoint.sh
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN chmod +x /entrypoint.sh

# Fix nginx configuration - ensure directory exists
RUN mkdir -p /run/nginx && \
    chown -R www-data:www-data /run/nginx

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisor.conf"]



# # Stage 1 – Composer dependencies (ignore pcntl check)
# FROM composer:2 AS composer-deps

# WORKDIR /app

# COPY composer.json composer.lock ./

# RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist --ignore-platform-reqs

# # Stage 2 – Build PHP extensions (pcntl + redis here)
# FROM php:8.3-fpm-alpine AS php-extensions

# RUN apk add --no-cache \
#     postgresql-dev \
#     libzip-dev \
#     && apk add --no-cache --virtual .build-deps \
#         $PHPIZE_DEPS \
#         autoconf \
#         automake \
#         make \
#         gcc \
#         g++ \
#         libtool \
#     && docker-php-ext-install pdo_pgsql zip pcntl \
#     && pecl install redis \
#     && docker-php-ext-enable redis \
#     && apk del .build-deps

# # Stage 3 – Final image
# FROM php:8.3-fpm-alpine

# # Runtime dependencies
# RUN apk add --no-cache \
#     nginx \
#     supervisor \
#     libzip \
#     postgresql-libs

# # Copy extensions from stage 2
# COPY --from=php-extensions /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
# COPY --from=php-extensions /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# # Copy Composer vendor from stage 1
# COPY --from=composer-deps /app/vendor/ /var/www/vendor/

# WORKDIR /var/www

# COPY . .

# # Permissions
# RUN chown -R www-data:www-data storage bootstrap/cache \
#     && chmod -R 775 storage bootstrap/cache

# # Copy configs
# COPY docker/nginx/default.conf /etc/nginx/nginx.conf
# COPY docker/php/entrypoint.sh /entrypoint.sh
# COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# RUN chmod +x /entrypoint.sh

# EXPOSE 80

# ENTRYPOINT ["/entrypoint.sh"]
# CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisor.conf"]




# # # Stage 1: Composer dependencies
# # FROM composer:2 AS composer

# # FROM php:8.3-fpm-alpine

# # # Install system dependencies
# # RUN apk add --no-cache \
# #     postgresql-dev \
# #     libzip-dev \
# #     zip \
# #     nginx \
# #     supervisor \
# #     && docker-php-ext-install pdo_pgsql zip

# # # Copy Composer from builder
# # COPY --from=composer /usr/bin/composer /usr/bin/composer

# # WORKDIR /var/www

# # COPY . .

# # # Install dependencies (no-dev for prod/Render)
# # RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# # # Permissions
# # RUN chown -R www-data:www-data storage bootstrap/cache

# # # Copy configs
# # COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
# # COPY docker/php/entrypoint.sh /entrypoint.sh
# # COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# # RUN chmod +x /entrypoint.sh

# # # Expose port 80 for Render / Nginx
# # EXPOSE 80

# # ENTRYPOINT ["/entrypoint.sh"]

# # # Default command (Render uses this; local compose overrides)
# # CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisor.conf"]


# # # ############################
# # # # Stage 1: Composer vendor
# # # ############################
# # # FROM composer:2 AS vendor

# # # WORKDIR /app

# # # COPY composer.json composer.lock ./

# # # RUN composer install \
# # #     --no-dev \
# # #     --prefer-dist \
# # #     --no-interaction \
# # #     --no-progress \
# # #     --no-scripts \
# # #     --optimize-autoloader

# # # # Copy full app so artisan exists
# # # COPY . .

# # # # Now scripts are safe
# # # RUN composer dump-autoload --optimize \
# # #  && php artisan package:discover --ansi

# # # ############################
# # # # Stage 2: PHP runtime
# # # ############################
# # # FROM php:8.3-fpm-alpine

# # # RUN apk add --no-cache \
# # #         bash \
# # #         postgresql-libs \
# # #         libzip \
# # #     && apk add --no-cache --virtual .build-deps \
# # #         $PHPIZE_DEPS \
# # #         postgresql-dev \
# # #         libzip-dev \
# # #     && docker-php-ext-install \
# # #         pdo_pgsql \
# # #         zip \
# # #         opcache \
# # #     && apk del .build-deps

# # # WORKDIR /var/www

# # # # Copy app + vendor (already built)
# # # COPY --from=vendor /app /var/www

# # # RUN chown -R www-data:www-data storage bootstrap/cache

# # # USER www-data

# # # CMD ["php-fpm"]



