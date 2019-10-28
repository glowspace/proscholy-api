FROM php:7.2-fpm

# I need libicu-dev in order to install intl extension
RUN apt-get update && \
    apt-get install -y git zip libicu-dev libpng-dev

# I need the intl extension so that Bagisto works (class NumberFormatter)
# ....... ok this is copied here from another project, so just let this be here, after all why shouldn't I keep it?
RUN docker-php-ext-install mbstring pdo pdo_mysql bcmath intl zip

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libgmp-dev libldap2-dev netcat sqlite3 libsqlite3-dev && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install gd gmp pcntl ldap sysvmsg exif
# && a2enmod rewrite

# Install PHP Imagick support
RUN apt-get install -qq curl libmcrypt-dev libjpeg-dev  libbz2-dev mcrypt
RUN apt-get install -y libmagickwand-dev --no-install-recommends

RUN pecl install imagick
RUN docker-php-ext-enable imagick

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

# Install Laravel Envoy
RUN composer global require "laravel/envoy=~1.5"
RUN apt-get install -y gnupg2

WORKDIR /var/www/html