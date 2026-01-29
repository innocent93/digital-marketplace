#!/bin/sh
set -e

# Permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

# Run migrations always (safe)
php artisan migrate --force

# Run seed only if a flag file doesn't exist (runs once)
if [ ! -f /var/www/storage/.seeded ]; then
    php artisan db:seed --force
    touch /var/www/storage/.seeded
fi

if [ "$FORCE_SEED" = "true" ] || [ ! -f /var/www/storage/.seeded ]; then
    php artisan db:seed --force
    touch /var/www/storage/.seeded
fi

# Clear caches
php artisan optimize:clear

exec "$@"