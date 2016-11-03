composer.install:
	docker run --rm -v ${PWD}:/app composer/composer install
