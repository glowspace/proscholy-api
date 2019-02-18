php artisan down --message="Probíhá aktualizace zpěvníku na novou verzi. Zkuste to později" --retry=60
composer dump-auto
php artisan config:cache
php artisan cache:clear
php artisan view:clear
php artisan migrate --force
php artisan up