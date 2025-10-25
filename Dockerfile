# ====================================
# 1. ベースイメージ
# ====================================
FROM php:8.3-apache

# ====================================
# 2. システム依存パッケージのインストール
# ====================================
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libonig-dev zip \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring

# ====================================
# 3. Apache設定（DocumentRootを/publicに変更）
# ====================================
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Directory ディレクティブの明示追加（エラー回避）
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

RUN a2enmod rewrite

# ====================================
# 4. Composer インストール
# ====================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ====================================
# 5. アプリケーションファイルのコピー
# ====================================
WORKDIR /var/www/html
COPY . .

# ====================================
# 6. 依存関係インストール & 権限設定
# ====================================
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ====================================
# 7. ポート設定 & 起動コマンド
# ====================================
EXPOSE 80
CMD ["apache2-foreground"]
