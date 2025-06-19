test:
	vendor/bin/phpunit tests
dump:
	composer dump-autoload
lint:
	phpcs --standard=PSR12 src/
lint-fix:
	phpcbf --standard=PSR12 src/
start:
	php -S localhost:8080 -t public public/index.php