FROM php:7-fpm

RUN usermod -u 1000 www-data

RUN apt-get update && apt-get install -y \
    libmcrypt-dev libicu-dev libpq-dev libxml2-dev \
    && docker-php-ext-install iconv mcrypt mbstring intl pdo pdo_mysql pdo_pgsql
RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/

WORKDIR /var/www/app/
