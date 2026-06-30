FROM php:8.3-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    libzip-dev \
    git \
    curl \
    nginx \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Configurar permisos
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
RUN chmod +x artisan

# Instalar dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Generar key
RUN php artisan key:generate

# Crear enlace simbólico para storage
RUN php artisan storage:link

# Configurar Nginx (APUNTA AL DIRECTORIO PUBLIC)
RUN echo "server {
    listen 10000;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}" > /etc/nginx/sites-available/default

# Crear directorio para PHP-FPM
RUN mkdir -p /run/php

EXPOSE 10000

# Iniciar PHP-FPM y Nginx
CMD php-fpm -D && nginx -g 'daemon off;'
