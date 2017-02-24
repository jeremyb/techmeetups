FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
        # for intl extension
        libicu-dev \
        # for postgres
        libpq-dev \
    && docker-php-ext-install opcache intl pdo pdo_pgsql

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/

WORKDIR /var/www/app/

RUN usermod -u 1000 www-data
USER www-data
