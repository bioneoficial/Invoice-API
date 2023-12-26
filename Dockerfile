FROM php:8.2-fpm

# Install OS packages for PHP extensions and the necessary libraries
RUN apt-get update && apt-get install -y \
        libbson-1.0 \
        libmongoc-1.0-0 \
        autoconf \
        pkg-config \
        libssl-dev \
        zip \
        unzip \
        git

# Install the MongoDB PHP extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Copy existing application directory contents
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Other PHP extensions and customizations can be added here
# ...

# Your existing Dockerfile commands can continue below
