FROM php:8.2-apache-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j2 bcmath exif gd intl mbstring pdo_mysql zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.8 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY . .
RUN mkdir -p storage/app/public storage/framework/cache/data \
    storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --prefer-dist \
       --no-interaction --optimize-autoloader --no-scripts \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x deploy/start.sh
COPY deploy/apache.conf /etc/apache2/sites-available/000-default.conf
COPY deploy/php.ini /usr/local/etc/php/conf.d/estaty.ini
EXPOSE 8080
CMD ["/var/www/html/deploy/start.sh"]
