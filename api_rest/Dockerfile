# Dockerfile para Laravel con Apache

# Usar la imagen oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# Dependencias de Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# Activar mod_rewrite
RUN a2enmod rewrite

# Cambiar DocumentRoot a public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# Cambiar DocumentRoot en Apache
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf
RUN sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Cambiar el directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer desde la imagen oficial de Composer a la imagen local
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# Copiar composer.json y composer.lock
COPY composer.json composer.lock ./
# Instalar dependencias de Composer (sin scripts) no-dev, mas ligero
RUN composer install --no-dev --optimize-autoloader --no-scripts
# Copiar el código fuente del proyecto al contenedor
COPY . .
# Instalar dependencias de Laravel
RUN composer dump-autoload --optimize
# Descubrir paquetes de Laravel que sean necesarios
RUN php artisan package:discover --ansi

# Publicar archivos estáticos necesarios de L5-Swagger (CSS/JS para la documentación)
RUN php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider" --force

# Crear directorios de almacenamiento y asignar permisos
RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache storage/framework/testing storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Asignar puerto para uso de Render // Aunque no fuerza a render a usar este puerto en concreto
EXPOSE 10000

# Reemplazar el puerto de escucha de Apache por el puerto del contenedor y arrancar Apache server
CMD sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground
