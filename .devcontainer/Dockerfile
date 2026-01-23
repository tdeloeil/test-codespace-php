FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye

RUN rm -f /etc/apt/sources.list.d/yarn.list

RUN apt-get update \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# COPY .devcontainer/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY .devcontainer/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini