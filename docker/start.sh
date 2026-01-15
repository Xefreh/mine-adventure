#!/bin/sh
set -e

# Run migrations
php /app/artisan migrate --force

# Clear and rebuild caches with runtime config
php /app/artisan config:cache
php /app/artisan route:cache
php /app/artisan view:cache

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisord.conf
