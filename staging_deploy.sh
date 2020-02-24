#!/usr/bin/env bash

LARAVEL='/var/www/html'

if cd releases ; then
    LAST=`readlink -f ../current` 
    LAST=${LAST##*/}

    echo 'Removing old releases but one last for backup'
    ls | grep -v ${LAST} | xargs rm -rfv
else
    echo "Releases folder not found, creating one"
    mkdir releases && cd releases
fi

DATESTAMP=`date +"%Y-%m-%d-%H-%M-%S"`

git clone --depth 1 --branch staging 'git@gitlab.com:proscholy/proscholy.cz.git' ${LARAVEL}/releases/${DATESTAMP} || 
    git clone --depth 1 --branch staging 'https://gitlab.com/proscholy/proscholy.cz.git' ${LARAVEL}/releases/${DATESTAMP}

if cd ${DATESTAMP} ; then 
    # git reset --hard staging

    echo "Linking storage directory"
    rm -rf storage
    ln -nfs ${LARAVEL}/storage ${LARAVEL}/releases/${DATESTAMP}/storage

    echo "Modifying access rights for staging for: storage, bootstrap/cache"
    chgrp -R www-data storage bootstrap/cache
    chmod -R ug+rwx storage bootstrap/cache

    mv .env.staging .env

    echo "Linking vendor and node_modules folders from the root folder"
    ln -s /var/www/html/vendor 
    ln -s /var/www/html/node_modules

    echo "Installing composer and yarn"
    php artisan down --message="Probíhá aktualizace zpěvníku na novou verzi. Zkuste to později" --retry=60
    
    composer install --optimize-autoloader
    composer dump-auto

    yarn install
    yarn run dev

    # rm -rf node_modules

    php artisan config:cache
    php artisan route:cache
    php artisan cache:clear
    php artisan view:clear
    php artisan migrate --force
    php artisan up

    ln -nfs ${LARAVEL}/releases/${DATESTAMP} ${LARAVEL}/current
else
    echo 'Cloning not successful, aborting the deploy'
fi

cd ${LARAVEL}
