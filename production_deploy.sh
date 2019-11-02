#!/usr/bin/env bash

LAST=`readlink -f current` 
LAST=${LAST##*/}

if cd releases ; then
    echo 'Removing old releases but one last for backup'
    ls | grep -v ${LAST} | xargs rm -rfv
else
    echo "Releases folder not found, creating one"
    mkdir releases && cd releases
fi

git clone --depth 1 'git@gitlab.com:proscholy/proscholy.cz.git' /var/www/html/releases/abcd
cd abcd
git reset --hard master

echo "Linking storage directory"
rm -rf storage
ln -nfs ../../storage storage

echo "Modifying access rights for production for: storage, bootstrap/cache"
chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

mv .env.production .env

echo "Installing composer and yarn"
composer install --optimize-autoloader --no-dev
composer dump-auto

yarn install
yarn run production

rm -rf node_modules

php artisan down --message="Probíhá aktualizace zpěvníku na novou verzi. Zkuste to později" --retry=60
php artisan config:cache
php artisan route:cache
php artisan cache:clear
php artisan view:clear
php artisan migrate --force
php artisan up

ln -nfs . ../../current