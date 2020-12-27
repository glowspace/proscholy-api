#!/usr/bin/env bash

if getopts "p" arg; then
    # echo "Updating docker using file $OPTARG"
    git checkout master
    git pull origin master

    docker-compose -f docker-compose.prod.yml up --build -d
    docker-compose -f docker-compose.prod.yml exec web bash -c "echo 'docker-ed in production env' \ 
    composer install --optimize-autoloader --no-dev
    
    yarn install
    yarn run production

    php artisan config:cache
    php artisan route:cache
    php artisan cache:clear
    php artisan view:clear
    php artisan migrate --force
    php artisan lighthouse:clear-cache
    php artisan lighthouse:cache

    curl nginx/reset-cache"
elif getopts "s" arg; then
    git checkout develop
    git pull origin develop

    docker-compose -f docker-compose.staging.yml up --build -d
    docker-compose -f docker-compose.staging.yml exec web bash -c "echo 'docker-ed in staging env' \ 
    composer install
    
    yarn install
    yarn run dev

    php artisan config:cache
    php artisan route:cache
    php artisan cache:clear
    php artisan view:clear
    php artisan migrate --force
    php artisan lighthouse:clear-cache
    php artisan lighthouse:cache"
fi