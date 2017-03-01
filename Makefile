composer:
	docker run --rm -v ${PWD}:/app composer/composer:alpine install

phpunit:
	docker exec -it pseudoorm ./vendor/bin/phpunit

docker.run:
	docker-compose up -d

docker.restart:
	docker-compose down
	make docker.run

start:
	make composer && make	docker.restart

