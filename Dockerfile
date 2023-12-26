FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
        libbson-1.0 \
        libmongoc-1.0-0 \
        autoconf \
        pkg-config \
        libssl-dev \
        zip \
        unzip \
        git

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install --no-interaction --prefer-dist --optimize-autoloader
