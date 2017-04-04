composer:
	docker run --rm -v ${PWD}:/app composer/composer:alpine install

phpunit:
	docker run -it --rm --name my-running-script -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.0-cli php ./vendor/phpunit/phpunit/phpunit

docker.run:
	docker-compose up -d

docker.restart:
	docker-compose down
	make docker.run

start:
	make composer && make	docker.restart

