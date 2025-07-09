test:
	vendor/bin/phpunit tests
dump:
	composer dump-autoload
lint:
	phpcs --standard=PSR12 src/
lint-fix:
	phpcbf --standard=PSR12 src/
start-local:
	php -S localhost:8080 -t public public/index.php

PORT ?= 8000

start:
    php -S 0.0.0.0:$(PORT) -t public public/index.php