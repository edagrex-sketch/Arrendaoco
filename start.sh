#!/bin/bash
set -e

echo "=== ArrendaOco Deployment ==="

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:..." ]; then
    php artisan key:generate --force
fi

# Link storage
php artisan storage:link --force

# Run migrations
php artisan migrate --force

# Clear & cache config
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start server
echo "=== Starting server on 0.0.0.0:$PORT ==="
php artisan serve --host=0.0.0.0 --port=$PORT
