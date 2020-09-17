FROM php:7.4.3-fpm

LABEL MAINTAINER="mghddev@gmail.com"

ARG WWW_DATA_USER
ARG ENVIRONMENT
ENV TZ=Asia/Tehran
ENV APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn

RUN rm /var/lib/apt/lists/* -vrf && \
    apt-get clean && apt-get update

RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

RUN apt-get update -y && apt-get install -y --no-install-recommends \
        g++ \
        apt-utils \
        libpcre3-dev \
        zlib1g-dev \
        libicu-dev \
        alien \
        libzip-dev \
        zip \
        git \
        libjpeg-dev \
        libpng-dev \
        libxml2-dev \
        libfreetype6-dev \
        libmcrypt-dev \
        libssl-dev \
        tzdata \
        libmagickwand-dev \
        libmagickcore-dev \
        wget \
    && echo "upload_max_filesize = 8M" > /usr/local/etc/php/conf.d/upload.ini \
    && docker-php-ext-install exif intl pdo_mysql bcmath pcntl soap \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure gd --with-jpeg --with-freetype &&  \
       docker-php-ext-install gd zip


## install composer inside container
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

## setup user group permission
RUN usermod -u ${WWW_DATA_USER} www-data && \
    groupmod -g ${WWW_DATA_USER} www-data && \
    chown -R www-data:www-data /var/www/

# change TimeZone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

WORKDIR /var/www/

## Cleanup
RUN apt-get purge -y \
    g++ \
    && rm -rf /var/lib/apt/lists/*
