FROM php:8.1-fpm

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
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www

# Copy source
COPY . .

# Install PHP deps
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Optimize Laravel
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Set Nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Expose port dari Railway
EXPOSE 8080

CMD service nginx start && php-fpm
