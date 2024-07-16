FROM php:8.0-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_pgsql

# Configure Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Copy custom php.ini configuration
COPY docker/php.ini /usr/local/etc/php/

# Expose port 80
EXPOSE 80
