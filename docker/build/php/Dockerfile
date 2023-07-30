FROM php:8.2-cli-alpine

ARG XDEBUG_MODE

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

RUN test -z $XDEBUG_MODE || pecl install xdebug
RUN test -z $XDEBUG_MODE || docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && cd /tmp && wget -c https://github.com/phrase/phraseapp-client/releases/download/1.13.0/phraseapp_linux_386 \
    && mv /tmp/phraseapp_linux_386 /usr/local/bin/phraseapp \
    && chmod +x /usr/local/bin/phraseapp

RUN composer self-update

WORKDIR /app

ENV XDEBUG_MODE=${XDEBUG_MODE:-off}