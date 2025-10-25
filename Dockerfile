# ベースイメージ：公式PHP + Apache
FROM php:8.3-apache

# システム依存ライブラリをインストール
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libzip-dev libonig-dev curl \
    && docker-php-ext-install pdo_pgsql pgsql zip mbstring

# Composer をインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apacheの設定
RUN a2enmod rewrite
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# 作業ディレクトリを設定
WORKDIR /var/www/html

# アプリケーションファイルをコピー
COPY . .

# キャッシュをクリアして依存関係をインストール
RUN composer install --no-dev --optimize-autoloader

# npmビルド（必要な場合）
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install && npm run build

# 権限設定（Render用）
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 環境変数
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV PORT=10000

# Apache設定反映
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ポート公開
EXPOSE 10000

# 起動コマンド
CMD ["apache2-foreground"]
