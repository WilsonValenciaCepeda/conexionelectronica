FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN php artisan key:generate

EXPOSE ${PORT:-8080}

CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]