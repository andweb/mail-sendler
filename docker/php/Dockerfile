FROM php:7.2-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends apt-utils \
    curl \
    wget \
    git \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    libzip-dev \
    zip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) pdo_mysql pdo_pgsql bcmath \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    # Install the PHP gd library
    && docker-php-ext-configure gd \
        --with-jpeg-dir=/usr/lib \
        --with-freetype-dir=/usr/include/freetype2 \
    && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN usermod -u 1000 www-data

WORKDIR /var/www/mail-sendler

CMD ["php-fpm"]