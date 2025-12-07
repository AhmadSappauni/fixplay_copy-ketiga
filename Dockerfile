FROM php:8.2-fpm

# Install dependencies system
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    zip \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# === Laravel ada di /var/www/html ===
WORKDIR /var/www/html

# Copy source code
COPY . .

# Install dependency PHP
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader \
 && php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear \
 && php artisan config:cache \
 && php artisan route:cache \
 && chmod -R 775 storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

# Nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Railway pakai port 8080
EXPOSE 8080

# Jalankan php-fpm & nginx
CMD sh -c "php-fpm & nginx -g 'daemon off;'"
