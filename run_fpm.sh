#!/usr/bin/env bash

# this script runs as an ENTRYPOINT in docker (production, staging)

chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

# composer install --optimize-autoloader --no-dev
# yarn

# php artisan config:cache
# php artisan route:cache
# php artisan cache:clear
# php artisan view:clear

php-fpm