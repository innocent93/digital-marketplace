#!/bin/bash

set -e

echo "Starting application..."

# # Wait for Redis
# echo "Waiting for Redis to be ready..."
# until nc -z redis 6379; do
#     echo "Redis not ready, waiting..."
#     sleep 1
# done
# echo "Redis is ready!"

# Wait for PostgreSQL
echo "Waiting for PostgreSQL to be ready..."
until nc -z postgres 5432; do
    echo "PostgreSQL not ready, waiting..."
    sleep 1
done
echo "PostgreSQL is ready!"

# Give PostgreSQL a moment to be fully ready
sleep 2

# Run Laravel setup commands
echo "Running Laravel setup..."
su -s /bin/sh www-data -c "php artisan config:clear"
su -s /bin/sh www-data -c "php artisan cache:clear"
su -s /bin/sh www-data -c "php artisan route:clear"
su -s /bin/sh www-data -c "php artisan view:clear"

# Cache configuration
su -s /bin/sh www-data -c "php artisan config:cache"

# Run migrations
echo "Running database migrations..."
su -s /bin/sh www-data -c "php artisan migrate --force"

# Seed database if in local environment
if [ "$APP_ENV" = "local" ]; then
    echo "Seeding database..."
    su -s /bin/sh www-data -c "php artisan db:seed --force"
fi

# Generate Scribe documentation
echo "Generating API documentation..."
su -s /bin/sh www-data -c "php artisan scribe:generate" || echo "Scribe documentation generation failed, continuing..."

# Create storage link
su -s /bin/sh www-data -c "php artisan storage:link" || echo "Storage link already exists"

echo "Starting supervisor..."
exec "$@"

# #!/bin/bash

# set -e

# echo "Starting application..."

# # Wait for Redis (if needed)
# if [ "$REDIS_HOST" = "redis" ]; then
#     echo "Waiting for Redis to be ready..."
#     until nc -z redis 6379; do
#         echo "Redis not ready, waiting..."
#         sleep 1
#     done
#     echo "Redis is ready!"
# fi

# # Wait for PostgreSQL (if needed)
# if [ "$DB_HOST" = "postgres" ]; then
#     echo "Waiting for PostgreSQL to be ready..."
#     until nc -z postgres 5432; do
#         echo "PostgreSQL not ready, waiting..."
#         sleep 1
#     done
#     echo "PostgreSQL is ready!"
# fi

# # Run Laravel setup commands if not in production or if forced
# if [ "$APP_ENV" != "production" ] || [ "$FORCE_SETUP" = "true" ]; then
#     echo "Running Laravel setup..."
#     php artisan config:cache
#     php artisan route:cache
#     php artisan view:cache
#     php artisan migrate --force
#     php artisan db:seed --force
# fi

# echo "Starting supervisor..."
# exec "$@"


# #!/bin/sh
# set -e

# # Permissions
# chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true
# chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

# # Run migrations always (safe)
# php artisan migrate --force

# # Run seed only if a flag file doesn't exist (runs once)
# if [ ! -f /var/www/storage/.seeded ]; then
#     php artisan db:seed --force
#     touch /var/www/storage/.seeded
# fi

# if [ "$FORCE_SEED" = "true" ] || [ ! -f /var/www/storage/.seeded ]; then
#     php artisan db:seed --force
#     touch /var/www/storage/.seeded
# fi

# # Clear caches
# php artisan optimize:clear

# exec "$@"