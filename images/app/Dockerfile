# Stage 1: Composer install
FROM composer:2 AS composer
WORKDIR /app
COPY ./volume/composer.* ./
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Application setup
FROM php:8.2-fpm-alpine
WORKDIR /app

RUN apk add --no-cache curl-dev openssl \
    && docker-php-ext-install curl
#    && docker-php-ext-install yaml

COPY --from=composer /app/vendor ./vendor

COPY ./volume /app

#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
RUN chmod +x /app/bin/server.php

#RUN addgroup -g 1001 app && adduser -u 1001 -G app -s /bin/sh -D app
#USER app

ENTRYPOINT /entrypoint.sh
