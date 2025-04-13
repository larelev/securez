FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory to Symfony root
WORKDIR /var/www/symfony

## Copy Symfony project files into container
#COPY ./docker/php/entrypoint.sh ./docker/php/entrypoint.sh

# Install PHP dependencies non-interactively
#RUN composer install --no-interaction --no-progress --optimize-autoloader

# Set correct permissions for cache/logs (especially for Docker dev env)
#RUN mkdir -p var/cache var/log && chmod -R 777 var

# Default: run Symfony server (for dev), or replace with php-fpm in prod
EXPOSE 9001
