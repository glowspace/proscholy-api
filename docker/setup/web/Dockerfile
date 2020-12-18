FROM php:7.4-fpm

# I need libicu-dev in order to install intl extension
RUN apt-get update && \
    apt-get install -y git zip 

RUN apt-get install -y libicu-dev libpng-dev libzip-dev

# RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libgmp-dev libldap2-dev netcat sqlite3 libsqlite3-dev && \
#     docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
#     docker-php-ext-install gd gmp pcntl ldap sysvmsg exif
# && a2enmod rewrite

# Install PHP Imagick support
# RUN apt-get install -qq curl libmcrypt-dev libjpeg-dev  libbz2-dev mcrypt
# RUN apt-get install -y libmagickwand-dev --no-install-recommends

# RUN pecl install imagick
# RUN docker-php-ext-enable imagick

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

# Install Laravel Envoy
# RUN composer global require "laravel/envoy=~1.5"
# RUN apt-get install -y gnupg2

# RUN echo "php_admin_flag[log_errors] = On">>/usr/local/etc/php-fpm.conf

# # XDebug
# RUN pecl install xdebug
# RUN docker-php-ext-enable xdebug
# RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# RUN echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# RUN echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# # any device can connect to xdebug
# RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# # RUN echo "xdebug.remote_host=192.168.88.170" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# # RUN echo "xdebug.idekey=mertblog.net" >> /usr/local/etc/php/conf.d/docker-ph

RUN docker-php-ext-install opcache