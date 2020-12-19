FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y git zip 

RUN apt-get install -y libicu-dev libpng-dev libzip-dev

# Composer
RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Node.js 12
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs

# Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install yarn

RUN docker-php-ext-install pdo pdo_mysql intl zip
RUN docker-php-ext-install opcache