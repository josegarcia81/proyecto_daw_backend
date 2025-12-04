# Base PHP 8.2 + Apache
FROM php:8.2-apache

# Instalar dependencias del sistema necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# Activar mod_rewrite para Laravel
RUN a2enmod rewrite

# Cambiar DocumentRoot a public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar composer.json y composer.lock para cache
COPY composer.json composer.lock ./

# Instalar dependencias sin ejecutar scripts (para evitar errores de artisan)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copiar el resto del proyecto
COPY . .

# Ejecutar scripts de Composer ahora que artisan existe
RUN composer dump-autoload --optimize
RUN php artisan package:discover --ansi

# Crear directorios y asignar permisos
RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache storage/framework/testing storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto 80
EXPOSE 80

# Comando de arranque de Apache
CMD ["apache2-foreground"]