FROM php:5.6-apache

# Install dependency for GD
RUN apt-get update && apt-get install -y libpng-dev

# MySQL support and GD
RUN docker-php-ext-install pdo pdo_mysql gd

RUN mkdir /data/

ENV APACHE_DOCUMENT_ROOT /src/public

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/;date.timezone =/date.timezone = "Europe\/Zurich"/g' "$PHP_INI_DIR/php.ini"
RUN sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/g' "$PHP_INI_DIR/php.ini"


RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite
RUN service apache2 restart 

# Copy the source code
COPY . /src/
RUN rm /src/config/autoload/zenddevelopertools.local.php
