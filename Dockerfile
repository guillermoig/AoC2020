FROM php:8.0.0-cli
COPY ./php.ini /usr/local/etc/php/php.ini
WORKDIR /var/www/html
