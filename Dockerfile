FROM php:8.2-apache

RUN a2enmod rewrite \
  && docker-php-ext-install pdo pdo_mysql

# Set DocumentRoot to /public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
  && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
