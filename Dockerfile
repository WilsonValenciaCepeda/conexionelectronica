FROM php:8.3-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    libzip-dev \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-interaction --optimize-autoloader

# Generar key de Laravel (se sobrescribirá con variable de entorno)
RUN php artisan key:generate

# Exponer el puerto 10000
EXPOSE 10000

# Iniciar el servidor
CMD php artisan serve --host=0.0.0.0 --port=10000
