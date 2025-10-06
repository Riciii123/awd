FROM php:8.1-apache

# Install any PHP extensions you need, e.g. mysqli, pdo, etc.
RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /var/www/html

# Copy your code, e.g. index.php, etc.
COPY . /var/www/html

# Expose port 80 (or whichever port Apache listens on)
EXPOSE 80

# Use the default Apache command
CMD ["apache2-foreground"]