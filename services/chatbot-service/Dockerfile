# syntax=docker/dockerfile:1
# Build context is the repo root (see docker-compose.yml) so this Dockerfile
# can share docker/nginx/default.conf and docker/supervisord.conf.
FROM composer:2 AS vendor
WORKDIR /app
COPY services/chatbot-service/composer.json services/chatbot-service/composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs
COPY services/chatbot-service/. .
RUN composer dump-autoload --optimize --no-dev

FROM php:8.2-fpm-alpine

# Slimmer than the other 4 services' Dockerfiles on purpose: this service
# has no database (no pdo_mysql), no image uploads (no gd/zip), and no
# RabbitMQ (no sockets) — see docs/architecture.md on why it's stateless.
# apcu: shared-memory storage for the Prometheus client (Phase 6), same
# reasoning as every other service.
RUN apk add --no-cache nginx supervisor $PHPIZE_DEPS \
    && docker-php-ext-install bcmath opcache \
    && pecl install apcu && docker-php-ext-enable apcu

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
