# syntax=docker/dockerfile:experimental

FROM composer:2.0.8 as build

FROM php:8.0.1-apache as base
ARG SSH_KEY

RUN apt-get update && apt-get install -y \
    g++ \
    ssh \
    git \
    vim \
    zip \
    wget \
    make \
    unzip \
    dialog \
    locales \
    libtool \
    optipng \
    autoconf \
    pngquant \
    gifsicle \
    ssl-cert \
    jpegoptim \
    apt-utils \
    libssl-dev \
    libzip-dev \
    libpng-dev \
    libxpm-dev \
    zlib1g-dev \
    libwebp-dev \
    libonig-dev \
    ca-certificates \
    build-essential \
    libfreetype6-dev \
    libmagickwand-dev \
    libjpeg62-turbo-dev
RUN apt-get clean

RUN mkdir -p -m 0600 /root/.ssh && ssh-keyscan -t rsa github.com > /root/.ssh/known_hosts
RUN echo "${SSH_KEY}" > /root/.ssh/id_rsa
RUN chmod 600 ~/.ssh/id_rsa

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN wget -P /etc/ssl/certs https://curl.haxx.se/ca/cacert.pem

RUN sed -n 's/^;date\.timezone[[:space:]]=.*$/date.timezone="UTC"/' "$PHP_INI_DIR/php.ini"
RUN sed -n "s/^;curl\.cainfo[[:space:]]=.*$/curl.cainfo=\/etc\/ssl\/certs\/cacert.pem/" "$PHP_INI_DIR/php.ini"
RUN sed -n "s/^;openssl\.cafile=.*$/openssl.cafile=\/etc\/ssl\/certs\/cacert.pem/" "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-configure gd \
        --with-webp \
        --with-freetype \
        --with-jpeg \
        --with-xpm

RUN docker-php-ext-install gd
RUN docker-php-ext-install pdo_mysql zip exif pcntl

RUN cd /tmp
RUN --mount=type=ssh,id=github git clone https://github.com/Imagick/imagick
RUN cd imagick && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini && \
    rm -rf /tmp/*

RUN export COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite headers brotli ssl
RUN service apache2 restart

EXPOSE 80
EXPOSE 443

FROM base
RUN mkdir /var/www/creativestreams.test
WORKDIR /var/www/creativestreams.test
COPY --chown=www-data:www-data . .
