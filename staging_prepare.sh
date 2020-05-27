#!/usr/bin/env bash

# git reset --hard staging

echo "Modifying access rights for staging for: storage, bootstrap/cache"
chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

# build yarn
yarn install
yarn run dev

echo "Installing composer"
php artisan down --message="Probíhá aktualizace zpěvníku na novou verzi. Zkuste to později" --retry=60

composer install --optimize-autoloader
composer dump-auto

php artisan config:cache
php artisan route:cache
php artisan cache:clear
php artisan view:clear
php artisan migrate --force
php artisan up