#!/bin/sh
set -eu
cd /var/www/html

# One mounted volume holds uploads, sessions and the installation marker.
: "${PERSISTENT_PATH:=/data}"
mkdir -p "$PERSISTENT_PATH/storage/app/public" \
    "$PERSISTENT_PATH/storage/framework/cache/data" \
    "$PERSISTENT_PATH/storage/framework/sessions" \
    "$PERSISTENT_PATH/storage/framework/views" "$PERSISTENT_PATH/storage/logs"
for directory in img front/img; do
    mkdir -p "$PERSISTENT_PATH/assets/$directory"
    if [ ! -L "public/assets/$directory" ]; then
        cp -an "public/assets/$directory/." "$PERSISTENT_PATH/assets/$directory/"
        mv "public/assets/$directory" "public/assets/${directory}.image-seed"
        ln -s "$PERSISTENT_PATH/assets/$directory" "public/assets/$directory"
    fi
done
if [ ! -L storage ]; then
    mv storage storage.image-seed
    ln -s "$PERSISTENT_PATH/storage" storage
fi
chown -R www-data:www-data "$PERSISTENT_PATH" bootstrap/cache
rm -f bootstrap/cache/config.php
php deploy/initialize.php
php artisan config:cache
php artisan view:cache

# Railway supplies PORT; Apache must listen on the same port.
APP_PORT="${PORT:-8080}"
case "$APP_PORT" in ''|*[!0-9]*) echo 'PORT must be numeric.' >&2; exit 1 ;; esac
sed -i "s/^Listen 80$/Listen $APP_PORT/" /etc/apache2/ports.conf
sed -i "s/:8080>/:$APP_PORT>/" /etc/apache2/sites-available/000-default.conf
exec apache2-foreground
