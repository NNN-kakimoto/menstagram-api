init:
	bundler install
	docker-sync start
	docker-compose build --no-cache
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php cp .env.dev .env
	docker-compose exec php php artisan key:generate
	make db
	docker-compose exec php php artisan storage:link

up:
	docker-compose up -d

down:
	docker-sync stop
	docker-compose down

db:
	docker-compose exec php php artisan migrate:fresh --seed

sh:
	docker-compose exec php bash

dbg:
	open http://localhost:8000/telescope

qual:
	php artisan insights

test:
	docker-compose exec php vendor/bin/phpunit
