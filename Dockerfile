FROM php:8.3-fpm
LABEL authors="Mojtaba Pakzad"

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
