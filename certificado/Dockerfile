FROM php:8.3-cli

# Install system and PHP dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    zip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy only composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install dependencies before copying the rest of the project
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the project
COPY . .

# Generate autoload files
RUN composer dump-autoload --optimize

# Expose the server port
EXPOSE 8000

# Start the server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]