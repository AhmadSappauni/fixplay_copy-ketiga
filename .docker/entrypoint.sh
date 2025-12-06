#!/usr/bin/env sh
set -e

cd /var/www/html

# Kalau belum ada .env, copy dari .env.example
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generate APP_KEY kalau belum ada
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
  php artisan key:generate --force
fi

# (Opsional) jalankan migrate otomatis kalau mau
# php artisan migrate --force || true

# Jalankan PHP-FPM sebagai daemon
php-fpm -D

# Jalankan nginx sebagai proses utama
nginx -g 'daemon off;'
