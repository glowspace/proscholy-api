# PHP FPM start script
cd /var/www/html
composer install --ignore-platform-reqs
php artisan storage:link
php artisan key:generate


php artisan migrate:fresh

# in order to properly do the seeding, we need to first create elasticsearch indexes
. elastic_createindex.sh
php artisan db:seed

php artisan view:clear
php artisan config:clear
# php artisan vendor:publish --all

# yarn run watch
chmod -R 777 storage

php-fpm
