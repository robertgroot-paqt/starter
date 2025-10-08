FROM php:8.4-fpm-alpine

# PHP extensions

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions pcntl

# Composer

COPY --from=composer /usr/bin/composer /usr/bin/composer
