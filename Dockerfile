FROM php:8-cli

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


ADD ./ /popo/
RUN rm -rf /popo/tests/logs/coverage/
RUN rm -rf /popo/tests/logs/coverage.xml
RUN rm -rf /popo/tests/App/Example/
RUN rm -rf /popo/tests/AppRedefinedNamespace/Example/
RUN rm -rf /popo/tests/AppWithNamespaceRoot/Example/

WORKDIR /popo
