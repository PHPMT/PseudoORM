composer.install:
	docker run --rm -v ${PWD}:/app composer/composer install

phpunit:
	docker exec -it pseudoorm ./vendor/bin/phpunit