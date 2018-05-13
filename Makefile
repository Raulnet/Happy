docker-start:
	docker-compose up -d
	make composer-install

docker-stop:
	docker-compose stop

# MALCOLM BASH
docker-bash:
	docker exec -it happy_api_1 bash

# COMPOSER INSTALL
composer-install:
	composer install

# CACHE CLEAR
cache-clear:
	./bin/console cache:clear --env=dev --no-debug --no-warmup

# PHPUNIT
phpunit:
	docker-compose -f docker-compose.yml run --rm api ./bin/console cache:clear --no-warmup --env=test
	docker-compose -f docker-compose.yml run --rm api ./vendor/bin/simple-phpunit

phpunit-coverage:
	docker-compose -f docker-compose.yml run --rm api ./bin/console cache:clear --no-warmup --env=test
	docker-compose -f docker-compose.yml run --rm api ./vendor/bin/simple-phpunit --coverage-html ./var/phpunit-coverage

rebuild:
	docker-compose down
	docker-compose build
	make docker-start
	sleep 10
	make cache-clear
	docker-compose -f docker-compose.yml run --rm api  ./vendor/bin/phinx m --no-interaction
	chown -R www-data ./var/cache

php-fixer:
	php-cs-fixer fix ./src -vvv --rules=@Symfony,-@PSR2
	php-cs-fixer fix ./tests -vvv --rules=@Symfony,-@PSR2

docker-reboot:
	sudo /etc/init.d/docker restart
