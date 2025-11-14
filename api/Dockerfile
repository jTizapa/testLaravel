FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl \
    libpq-dev libonig-dev \
    libzip-dev zlib1g-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip

WORKDIR /var/www/html
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Ensure Laravel cache directories exist for package:discover during build
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache
RUN composer install

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
