FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    zip \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Node.js (necesario para Breeze y Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

# Instalar dependencias de Node primero (opcional)
RUN npm install --ignore-scripts

# Luego Composer
RUN composer install --no-interaction --no-dev

RUN php artisan key:generate

EXPOSE 10000



RUN composer diagnose
RUN composer validate
RUN composer why-not phpCMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000"]
