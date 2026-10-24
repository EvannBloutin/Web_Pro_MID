FROM node:22-bookworm AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

COPY . .
RUN npm run build

FROM dunglas/frankenphp:php8.4-bookworm

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends libsqlite3-dev unzip \
    && docker-php-ext-install pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN rm -f bootstrap/cache/*.php \
    && composer dump-autoload --no-dev --optimize \
    && mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && touch database/database.sqlite \
    && chown -R www-data:www-data database storage bootstrap/cache

COPY Caddyfile /etc/caddy/Caddyfile

EXPOSE 8080

CMD ["sh", "-c", "php artisan migrate --force && php artisan optimize && frankenphp run --config /etc/caddy/Caddyfile"]