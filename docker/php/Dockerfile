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
    unzip \
    libgearman-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) pdo_mysql pdo_pgsql bcmath \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    # Install the PHP gd library
    && docker-php-ext-configure gd \
        --with-jpeg-dir=/usr/lib \
        --with-freetype-dir=/usr/include/freetype2 \
    && docker-php-ext-install gd

RUN apt-get -y --allow-unauthenticated install \
    && cd /tmp \
    && wget https://github.com/wcgallego/pecl-gearman/archive/gearman-2.0.3.zip \
    && unzip gearman-2.0.3.zip \
    && mv pecl-gearman-gearman-2.0.3 pecl-gearman \
    && cd pecl-gearman \
    && phpize \
    && ./configure \
    && make -j$(nproc) \
    && make install \
    && cd / \
    && rm /tmp/gearman-2.0.3.zip \
    && rm -r /tmp/pecl-gearman \
    && docker-php-ext-enable gearman

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN usermod -u 1000 www-data

WORKDIR /var/www/mail-sendler

CMD ["php-fpm"]