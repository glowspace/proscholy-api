FROM php:8.3-fpm

LABEL maintainer="Miroslav Sery"

WORKDIR /var/www/html

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y git zip libicu-dev libpng-dev libzip-dev cron \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

RUN npm install --global yarn

RUN docker-php-ext-install pdo pdo_mysql intl zip
RUN docker-php-ext-install opcache

RUN apt-get install -y supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

COPY php.ini /usr/local/etc/php/conf.d/custom.ini
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www2.conf

RUN touch /var/log/fpm-php.www.log && chmod 666 /var/log/fpm-php.www.log

COPY crontab /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron
RUN touch /var/log/cron.log
CMD printenv > /etc/environment


EXPOSE 8000

ENTRYPOINT ["start-container"]
