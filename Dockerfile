FROM php:8.0-cli

RUN apt-get upgrade -y && apt-get update \
    && apt-get install -y \
      wget \
      zip \
      unzip


RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && cd /tmp && wget -c https://github.com/phrase/phraseapp-client/releases/download/1.13.0/phraseapp_linux_386 \
    && mv /tmp/phraseapp_linux_386 /usr/local/bin/phraseapp \
    && chmod +x /usr/local/bin/phraseapp

RUN composer self-update


ADD ./ /popo-app/
RUN rm /popo-app/popo.sh
RUN rm /popo-app/Dockerfile
RUN rm -rf /popo-app/tests/
RUN rm -rf /popo-app/doc/

WORKDIR /popo-app/
