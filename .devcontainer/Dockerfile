FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye
# FROM php:8.2-apache-bullseye

# RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarnkey.gpg >/dev/null

RUN rm -f /etc/apt/sources.list.d/yarn.list

RUN apt-get update \
    && docker-php-ext-install pdo pdo_mysql xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite