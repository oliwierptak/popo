FROM php:8.2-cli-alpine

RUN apk add --no-cache \
      wget \
      zip \
      unzip

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && cd /tmp && wget -c https://github.com/phrase/phraseapp-client/releases/download/1.13.0/phraseapp_linux_386 \
    && mv /tmp/phraseapp_linux_386 /usr/local/bin/phraseapp \
    && chmod +x /usr/local/bin/phraseapp

RUN composer self-update

WORKDIR /app
COPY ./src /app/src/
COPY ./bin /app/bin/
COPY ./vendor /app/vendor

RUN chmod +x /app/bin/*
