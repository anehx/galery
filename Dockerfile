FROM php:5.6-apache
 
RUN apt-get update && docker-php-ext-install mysql pdo_mysql
 
RUN a2enmod rewrite
 
WORKDIR /var/www/galery
