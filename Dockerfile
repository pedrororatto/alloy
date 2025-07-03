FROM composer:2.7 as vendor
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --ignore-platform-reqs --no-scripts --no-autoloader

FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    sqlite \
    sqlite-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    g++ \
    make \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    linux-headers \
    shadow \
    git \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_sqlite intl zip
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=vendor /usr/bin/composer /usr/bin/composer

COPY --from=vendor /var/www/html/vendor ./vendor

COPY . .

RUN composer install
RUN npm install

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
