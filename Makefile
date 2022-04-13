
production-deploy:
	# # echo "Updating docker using file $OPTARG"
	git checkout master -f
	git pull origin master
	git submodule init
	git submodule update --recursive --remote

	docker-compose -f docker-compose.prod.yml up --build -d
	docker-compose -f docker-compose.prod.yml exec -T web composer install --optimize-autoloader --no-dev 

	docker-compose -f docker-compose.prod.yml exec -T web yarn install
	docker-compose -f docker-compose.prod.yml exec -T web yarn run production
	
	docker-compose -f docker-compose.prod.yml exec -T web php artisan config:cache
	# this command is allowed to fail (- sign)
	-docker-compose -f docker-compose.prod.yml exec -T web php artisan route:cache
	docker-compose -f docker-compose.prod.yml exec -T web php artisan cache:clear
	docker-compose -f docker-compose.prod.yml exec -T web php artisan view:clear
	docker-compose -f docker-compose.prod.yml exec -T web php artisan migrate --force
	docker-compose -f docker-compose.prod.yml exec -T web php artisan lighthouse:clear-cache
	docker-compose -f docker-compose.prod.yml exec -T web php artisan lighthouse:cache
	docker-compose -f docker-compose.prod.yml exec -T web php artisan queue:restart
	docker-compose -f docker-compose.prod.yml exec -T web curl nginx/reset-cache
	
	docker-compose -f docker-compose.prod.yml exec -T web php artisan queue:restart

staging-deploy:
	git checkout develop -f
	git pull origin develop
	git submodule init
	git submodule update --recursive --remote

	docker-compose -f docker-compose.staging.yml up --build -d
	docker-compose -f docker-compose.staging.yml exec -T web composer install
	
	docker-compose -f docker-compose.staging.yml exec -T web yarn install
	docker-compose -f docker-compose.staging.yml exec -T web yarn run dev
	docker-compose -f docker-compose.staging.yml exec -T web php artisan config:cache
	# this command is allowed to fail (- sign)
	-docker-compose -f docker-compose.staging.yml exec -T web php artisan route:cache
	docker-compose -f docker-compose.staging.yml exec -T web php artisan cache:clear
	docker-compose -f docker-compose.staging.yml exec -T web php artisan view:clear
	docker-compose -f docker-compose.staging.yml exec -T web php artisan migrate --force
	docker-compose -f docker-compose.staging.yml exec -T web php artisan lighthouse:clear-cache
	docker-compose -f docker-compose.staging.yml exec -T web php artisan lighthouse:cache
	docker-compose -f docker-compose.staging.yml exec -T web php artisan queue:restart
	docker-compose -f docker-compose.staging.yml exec -T chmod +x elastic_update.sh && ./elastic_update

	docker-compose -f docker-compose.staging.yml exec -T web curl nginx/reset-cache
