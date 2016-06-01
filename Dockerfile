FROM php:5.6-apache

RUN apt-get update && \
    apt-get install -y libmagickwand-dev --no-install-recommends && \
    docker-php-ext-install mysql pdo_mysql && \
    pecl install imagick && \
    pecl install xdebug && \
    docker-php-ext-enable imagick xdebug

RUN a2enmod rewrite

WORKDIR /var/www/galery
