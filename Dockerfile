FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install Composer dependencies
# RUN php artisan config:clear
# RUN php artisan cache:clear
# RUN php artisan optimize:clear
# RUN php artisan route:clear
# RUN php artisan view:clear

RUN composer install

RUN composer require laravel/inertia-laravel  laravel/jetstream laravel/livewire

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 8000
CMD ["php-fpm"] 