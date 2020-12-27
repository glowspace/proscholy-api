
production-deploy:
	# # echo "Updating docker using file $OPTARG"
	git checkout master
	git pull origin master

	docker-compose -f docker-compose.prod.yml up --build -d
	docker-compose -f docker-compose.prod.yml exec web composer install --optimize-autoloader --no-dev 

	docker-compose -f docker-compose.prod.yml exec web yarn install
	docker-compose -f docker-compose.prod.yml exec web yarn run production
	
	docker-compose -f docker-compose.prod.yml exec web php artisan config:cache
	docker-compose -f docker-compose.prod.yml exec web php artisan route:cache
	docker-compose -f docker-compose.prod.yml exec web php artisan cache:clear
	docker-compose -f docker-compose.prod.yml exec web php artisan view:clear
	docker-compose -f docker-compose.prod.yml exec web php artisan migrate --force
	docker-compose -f docker-compose.prod.yml exec web php artisan lighthouse:clear-cache
	docker-compose -f docker-compose.prod.yml exec web php artisan lighthouse:cache

	docker-compose -f docker-compose.prod.yml exec web curl nginx/reset-cache

staging-deploy:
	git checkout develop
	git pull origin develop

	docker-compose -f docker-compose.staging.yml up --build -d
	docker-compose -f docker-compose.staging.yml exec web composer install
	
	docker-compose -f docker-compose.staging.yml exec web yarn install
	docker-compose -f docker-compose.staging.yml exec web yarn run dev
	docker-compose -f docker-compose.staging.yml exec web php artisan config:cache
	docker-compose -f docker-compose.staging.yml exec web php artisan route:cache
	docker-compose -f docker-compose.staging.yml exec web php artisan cache:clear
	docker-compose -f docker-compose.staging.yml exec web php artisan view:clear
	docker-compose -f docker-compose.staging.yml exec web php artisan migrate --force
	docker-compose -f docker-compose.staging.yml exec web php artisan lighthouse:clear-cache
	docker-compose -f docker-compose.staging.yml exec web php artisan lighthouse:cache"