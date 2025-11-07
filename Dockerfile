# ======================================
# Laravel + PHP + SQLite + Vite build for Render
# ======================================

FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libsqlite3-dev nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Composer install (production optimized)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Install Node dependencies and build assets (for Vite)
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Laravel setup
# ※ APP_KEY は Render Environment で設定済みなので生成しない！
RUN mkdir -p storage bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 666 database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache public/build database

# Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# キャッシュ最適化（任意）
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Expose and start
EXPOSE 80
CMD ["apache2-foreground"]
