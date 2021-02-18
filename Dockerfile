FROM php:7.3-apache

#Update & Install exts
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git iputils-ping
RUN apt-get update
RUN apt-get install -y libzip-dev zip unzip nano libmemcached-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev libgmp-dev && rm -r /var/lib/apt/lists/*
RUN pecl install memcached
RUN docker-php-ext-configure zip --with-libzip
RUN echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install pdo pdo_mysql mysqli zip gd exif

#enable models
RUN a2enmod rewrite
RUN a2enmod headers

#Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=. --filename=composer
RUN mv composer /usr/local/bin/
