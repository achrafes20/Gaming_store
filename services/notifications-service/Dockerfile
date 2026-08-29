# syntax=docker/dockerfile:1
# Build context is the repo root (see docker-compose.yml).
FROM composer:2 AS vendor
WORKDIR /app
COPY services/notifications-service/composer.json services/notifications-service/composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs
COPY services/notifications-service/. .
RUN composer dump-autoload --optimize --no-dev

FROM php:8.2-cli-alpine

RUN apk add --no-cache libpng-dev icu-dev linux-headers \
    && docker-php-ext-install pdo_mysql bcmath sockets

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Long-running consumer, not an HTTP server — see docs/architecture.md.
CMD ["php", "artisan", "events:consume"]
