FROM php:8.2-fpm

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chmod +x /home 
RUN useradd -G www-data,root -u $uid -d /home $user
RUN mkdir -p /home/.composer && \
    chown -R $user:$user /home

WORKDIR /var/www

USER $user
