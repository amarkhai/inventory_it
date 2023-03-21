up:
	docker-compose up -d

restart:
	docker-compose stop && docker-compose up -d

build-up:
	docker-compose up -d --build && docker exec -it inventory_app_php-fpm composer install

bash-php-fpm:
	docker-compose exec php-fpm bash
