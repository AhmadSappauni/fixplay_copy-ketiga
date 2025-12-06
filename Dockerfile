# syntax=docker/dockerfile:1

#############################
# 1) BUILD: install vendor  #
#############################
FROM composer:2 AS vendor

WORKDIR /app

# Copy file composer dulu
COPY composer.json composer.lock ./

# Install dependency (tanpa dev, tanpa script)
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts

# Copy semua source code
COPY . .

#############################
# 2) RUNTIME: PHP-FPM + NGINX
#############################
FROM php:8.2-fpm-bullseye

# Install paket OS + Nginx + extension PHP
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy konfigurasi nginx
COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/default.conf /etc/nginx/conf.d/default.conf

# Copy source Laravel dari stage vendor
WORKDIR /var/www/html
COPY --from=vendor /app ./

# Permission untuk storage dan cache
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Environment default (bisa di‑override dari Railway)
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr

# Port yang dipakai Nginx
EXPOSE 80

# Entry script untuk jalanin php-fpm + nginx
COPY .docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]
