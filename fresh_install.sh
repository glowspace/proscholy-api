#!/bin/bash

# run from the root folder of the project (either in Docker or not)
if [ -f ".env" ]; then
    echo "Lets goo";
else
    ls;
    echo "Probably not in the laravel root folder. (.env file not found)";
    exit;
fi

composer install --ignore-platform-reqs
yarn

# if there is the storage linked, then remove and link again
# i.e. when switching docker and non-docker, the paths don't match
rm -f ./public/storage && php artisan storage:link

php artisan key:generate
php artisan migrate:fresh --seed

php artisan view:clear
php artisan config:clear
php artisan vendor:publish --all

chmod -R 777 storage

if [ "$SCOUT_DRIVER" == "elastic" ] && [ "$DISABLE_ELASTIC" != "true" ]; then
    # todo: build a new index, if necessary, import models with scout
fi