FROM php:7.3-fpm

# Install cron
RUN apt-get update
RUN apt-get install -y cron

RUN docker-php-ext-install mbstring pdo pdo_mysql
RUN docker-php-ext-install mysqli 

ADD crontab /etc/cron.d/laravel-cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/laravel-cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

CMD printenv > /etc/environment && echo "cron starting..." && (cron -f)