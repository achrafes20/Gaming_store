# syntax=docker/dockerfile:1
# Build context is the repo root (see docker-compose.yml) so this Dockerfile
# can share docker/nginx/default.conf and docker/supervisord.conf.
FROM composer:2 AS vendor
WORKDIR /app
COPY services/orders-service/composer.json services/orders-service/composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs
COPY services/orders-service/. .
RUN composer dump-autoload --optimize --no-dev

FROM php:8.2-fpm-alpine

# apcu: shared-memory storage for the Prometheus client (Phase 6) — metrics
# recorded by one php-fpm worker must be visible to whichever worker nginx
# routes the /metrics scrape to; APCu is per-container shared memory,
# exactly the scope a single scraped Pod/container needs.
RUN apk add --no-cache nginx supervisor libpng-dev libzip-dev icu-dev linux-headers $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql gd zip bcmath opcache sockets \
    && pecl install apcu && docker-php-ext-enable apcu

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
