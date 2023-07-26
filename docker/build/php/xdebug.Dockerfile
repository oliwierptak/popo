FROM php:8.2-cli-alpine

RUN apk add --no-cache --virtual build-essentials --update \
    linux-headers \
    icu-dev icu-libs \
    zlib-dev g++ make automake autoconf libzip-dev

RUN apk add --no-cache \
    ncurses \
    bash \
    wget \
    zip \
    unzip \
    vim

RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && cd /tmp && wget -c https://github.com/phrase/phraseapp-client/releases/download/1.13.0/phraseapp_linux_386 \
    && mv /tmp/phraseapp_linux_386 /usr/local/bin/phraseapp \
    && chmod +x /usr/local/bin/phraseapp

RUN composer self-update

WORKDIR /app

ENV SHELL_MODE="x"