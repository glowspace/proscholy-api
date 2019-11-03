# # PHP FPM start script

cd current

echo $PWD

# composer install --no-dev --ignore-platform-reqs
# php artisan storage:link
# php artisan key:generate

# #sleep 30
# # so far do the import
# php artisan migrate:fresh --seed
# php artisan view:clear
# php artisan config:clear
# # php artisan vendor:publish --all

# # yarn run watch
# chmod -R 777 storage

# chgrp -R www-data storage bootstrap/cache
# chmod -R ug+rwx storage bootstrap/cache

php-fpm
