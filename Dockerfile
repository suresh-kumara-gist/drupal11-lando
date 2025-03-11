# Use the official Drupal PHP 8.3 FPM image based on Alpine 3.21
FROM drupal:php8.3-fpm-alpine3.21

# Install necessary system dependencies and PHP extensions
RUN apk --no-cache add \
    bash \
    curl \
    git \
    mysql-client \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxml2-dev \
    libzip-dev \
    apache2 \
    apache2-utils \
    && apk add --no-cache --virtual .build-deps \
    gcc \
    g++ \
    make \
    autoconf \
    libc-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo pdo_mysql zip xml opcache \
    && docker-php-ext-enable opcache \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# Install Composer (PHP dependency manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory to /opt/drupal
WORKDIR /opt/drupal

# Copy the Drupal project files into the container
COPY ./drupal /opt/drupal

# Install Drupal and its dependencies via Composer
RUN composer install

# Set up Apache with Drupal
RUN ln -s /opt/drupal/web /var/www/html

# Enable Apache mod_rewrite for clean URLs in Drupal
RUN sed -i -e 's/^\(LoadModule rewrite_module.*\)/#\1/' /etc/apache2/httpd.conf && \
    echo "LoadModule rewrite_module modules/mod_rewrite.so" >> /etc/apache2/httpd.conf

# Set appropriate permissions for Drupal files
RUN chown -R www-data:www-data /opt/drupal

# Expose the HTTP port (default port for Apache)
EXPOSE 80 443

# Start Apache in the foreground
CMD ["httpd", "-D", "FOREGROUND"]

