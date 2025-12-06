# --- BASE IMAGE --------------------------------------------------
FROM php:8.2-apache

# --- SYSTEM DEPENDENCIES (buat gd, zip, dll) ---------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        gd \
        zip \
        bcmath \
        intl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# --- ATUR DOCUMENT ROOT KE /public -------------------------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

# --- PASANG COMPOSER ---------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- SET WORKDIR -------------------------------------------------
WORKDIR /var/www/html

# --- COPY FILE COMPOSER & INSTALL DEPENDENCY ---------------------
# (copy dulu biar layer cache lebih irit)
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# --- COPY SELURUH SOURCE LARAVEL --------------------------------
COPY . .

# Pastikan folder penting bisa ditulis web server
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

# OPTIONAL: cache config/route (kalau error karena env/DB, boleh dihapus)
RUN php artisan config:clear || true && \
    php artisan route:clear  || true

# Expose port default Apache
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
