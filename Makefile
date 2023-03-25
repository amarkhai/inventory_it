up:
	docker-compose up -d

restart:
	docker-compose stop && docker-compose up -d

build-up:
	docker-compose up -d --build && docker-compose exec php-fpm composer install

bash-php-fpm:
	docker-compose exec php-fpm bash
