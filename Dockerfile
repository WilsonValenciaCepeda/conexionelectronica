# Usar imagen oficial de PHP con Apache
FROM php:8.3-apache

# ============================================
# 1. INSTALAR DEPENDENCIAS DEL SISTEMA
# ============================================
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    libzip-dev \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

# ============================================
# 2. HABILITAR MOD_REWRITE DE APACHE
# ============================================
RUN a2enmod rewrite

# ============================================
# 3. INSTALAR COMPOSER
# ============================================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ============================================
# 4. ESTABLECER DIRECTORIO DE TRABAJO
# ============================================
WORKDIR /var/www/html

# ============================================
# 5. COPIAR ARCHIVOS DEL PROYECTO
# ============================================
COPY . .

# ============================================
# 6. CONFIGURAR PERMISOS (¡CRÍTICO!)
# ============================================
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
RUN chmod +x artisan

# ============================================
# 7. CREAR ENLACE SIMBÓLICO PARA STORAGE
# ============================================
RUN php artisan storage:link

# ============================================
# 8. INSTALAR DEPENDENCIAS DE PHP
# ============================================
RUN composer install --no-interaction --optimize-autoloader --no-dev

# ============================================
# 9. GENERAR APP_KEY
# ============================================
RUN php artisan key:generate

# ============================================
# 10. LIMPIAR Y CACHEAR CONFIGURACIÓN
# ============================================
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# ============================================
# 11. CONFIGURAR APACHE PARA USAR PUERTO 10000
# ============================================
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# ============================================
# 12. EXPONER PUERTO 10000
# ============================================
EXPOSE 10000

# ============================================
# 13. INICIAR APACHE
# ============================================
CMD ["apache2-foreground"]
