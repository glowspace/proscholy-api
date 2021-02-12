FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y git zip 

RUN apt-get install -y libpng-dev libzip-dev libicu-dev

RUN docker-php-ext-install pdo pdo_mysql intl zip opcache

# https://stackoverflow.com/questions/53811567/use-of-undefined-constant-sigkill-assumed-sigkill-in-laravel-5-7-queue
RUN docker-php-ext-install pcntl