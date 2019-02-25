#!/usr/bin/env bash

php artisan down --message="Probíhá aktualizace zpěvníku na novou verzi. Zkuste to později" --retry=60

git pull
composer install --no-dev
composer dump-auto
npm install
npm run production
php artisan config:cache
php artisan cache:clear
php artisan view:clear
php artisan migrate --force
php artisan up