FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye

RUN apt-get update && apt-get install -y \
    pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite