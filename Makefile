.PHONY: start stop test

start:
	docker-compose up -d --build

stop:
	docker-compose down

bash:
	docker-compose exec php bash

test:
	docker-compose exec php ./vendor/bin/phpunit
