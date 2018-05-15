FROM php:7.2-apache

# install
RUN apt-get update && apt-get install -y \
    ghostscript \
    git \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libicu-dev \
    libfontconfig1 \
    libxrender1 \
    libxml2 \
    libxml2-dev \
    wget gnupg \
  && docker-php-source extract \
  && docker-php-ext-install mysqli pdo_mysql zip gd \
  && docker-php-source delete

# Enable mod rewrite
RUN a2enmod actions rewrite headers expires

RUN { \
    echo '<VirtualHost *:80>'; \
    echo 'ServerAdmin webmaster@localhost'; \
    echo 'DocumentRoot /var/www/html/public'; \
    echo '<Directory /var/www/html/public>'; \
    echo 'AllowOverride All'; \
    echo 'Order Allow,Deny'; \
    echo 'Allow from All'; \
    echo '<IfModule mod_rewrite.c>'; \
    echo 'Header set Access-Control-Allow-Origin *'; \
    echo 'Options -MultiViews'; \
    echo 'RewriteEngine On'; \
    echo 'RewriteCond %{REQUEST_FILENAME} !-f'; \
    echo 'RewriteRule ^(.*)$ index.php [QSA,L]'; \
    echo 'RewriteCond %{HTTP:Authorization} ^(.+)$'; \
    echo 'RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]'; \
    echo '</IfModule>'; \
    echo '</Directory>'; \
    echo '<Directory /var/www/html/public/bundles>'; \
    echo '<IfModule mod_rewrite.c>'; \
    echo 'RewriteEngine Off'; \
    echo '</IfModule>'; \
    echo '</Directory>'; \
    echo 'ErrorLog ${APACHE_LOG_DIR}/error.log'; \
    echo 'CustomLog ${APACHE_LOG_DIR}/access.log combined'; \
    echo '</VirtualHost>'; \
    } | tee "$APACHE_CONFDIR/sites-available/000-default.conf"


#PHP config
ADD docker/php.ini /usr/local/etc/php

# Composer
ADD composer.json /var/www/html
RUN apt-get install wget locales --assume-yes && echo "en_US.UTF-8 UTF-8" >> /etc/locale.gen && locale-gen && ssh-keygen -b 2048 -t rsa -f /root/.ssh/id_rsa -q -N ""
RUN php -r "readfile('https://getcomposer.org/installer');" | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer global require "hirak/prestissimo:^0.3" \
  && composer install --no-autoloader --no-scripts

# Add code
ADD . /var/www/html

# Xdebug - uncomment and rebuild image if you want it !
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# Dump autolad
RUN composer install --optimize-autoloader
RUN composer dump-autoload -o -a
RUN /var/www/html/bin/console cache:clear --env=prod --no-warmup
RUN /var/www/html/bin/console cache:warmup --env=prod --no-debug
RUN chown -R www-data /var/www/html/var/cache
