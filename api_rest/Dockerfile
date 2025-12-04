FROM php:8.2-apache

# Dependencias de Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# Activar mod_rewrite
RUN a2enmod rewrite

# Cambiar DocumentRoot a public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf
RUN sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN composer dump-autoload --optimize
RUN php artisan package:discover --ansi

RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache storage/framework/testing storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Render usará este puerto dinámicamente
EXPOSE 10000

# CMD corregido
CMD sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground
