# ======================================
# Laravel + PHP + SQLite + Vite for Render
# ======================================

FROM php:8.3-apache

# --- 基本セットアップ ---
RUN apt-get update && apt-get install -y \
    zip unzip git curl nodejs npm libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Apache rewrite有効化
RUN a2enmod rewrite

# --- 作業ディレクトリ設定 ---
WORKDIR /var/www/html

# --- Composerのセットアップ ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- ソースコードコピー ---
COPY . .

# --- PHP依存関係インストール ---
RUN composer install --no-dev --optimize-autoloader

# --- Node (Vite) ビルド ---
RUN npm install && npm run build

# --- Laravelセットアップ ---
RUN php artisan key:generate || true

# --- パーミッション修正 ---
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache \
    && touch database/database.sqlite && chmod 666 database/database.sqlite

# --- Apache ドキュメントルート設定 ---
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# --- ポート公開 + 起動コマンド ---
EXPOSE 80
CMD ["apache2-foreground"]
