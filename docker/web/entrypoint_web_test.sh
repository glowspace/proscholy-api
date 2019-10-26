# PHP FPM start script
cd /var/www/html
composer install --ignore-platform-reqs
php artisan storage:link
php artisan key:generate

#sleep 30
# so far do the import
php artisan migrate:fresh --seed
php artisan view:clear
php artisan config:clear
php artisan vendor:publish --all

#yarn
chmod -R 777 storage

php-fpm
