#!/usr/bin/env bash

php artisan down --message="Probíhá aktualizace zpěvníku na novou verzi. Zkuste to později" --retry=60
git reset --hard
git pull
composer install --optimize-autoloader --no-dev
composer dump-auto
yarn install
yarn run production
php artisan config:cache
php artisan route:cache
php artisan cache:clear
php artisan view:clear
php artisan migrate --force
php artisan up