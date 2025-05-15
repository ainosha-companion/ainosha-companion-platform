# Use PHP-FPM base image with specific version
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    xfonts-75dpi \
    xfonts-base \
    libxrender1 \
    libfontconfig1 \
    libxext6 \
    zip \
    unzip \
    wkhtmltopdf \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application files
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Set file permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Create directories first
RUN mkdir -p /usr/local/etc/php/conf.d \
    && mkdir -p /usr/local/etc/php-fpm.d

# Copy configuration files one by one
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Switch to non-root user
USER www-data

EXPOSE 9000

CMD ["php-fpm"]
