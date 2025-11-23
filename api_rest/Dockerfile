# Base PHP 8.2 + Apache
FROM php:8.2-apache

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# Activar mod_rewrite
RUN a2enmod rewrite

# Cambiar DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar solo composer.json primero para cache
COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Copiar el resto del proyecto
COPY . .

# Permisos
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto
EXPOSE 80

# CMD
CMD ["apache2-foreground"]