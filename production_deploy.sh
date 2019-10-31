#!/usr/bin/env bash

LAST=`readlink -f current` 
LAST=${LAST##*/}

cd releases
echo 'Removing old releases but one last for backup'
ls | grep -v ${LAST} | xargs rm -rfv

git clone --depth 1 'git@gitlab.com:proscholy/proscholy.cz.git' /var/www/html/releases/abcd
cd abcd
git reset --hard master

echo "Linking storage directory"
rm -rf storage
ln -nfs ../../storage storage

mv .env.production .env

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