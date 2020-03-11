console:
	php artisan tinker

serve:
	php artisan serve

log:
	tail -f storage/logs/laravel.log

test:
	composer phpunit

lint:
	composer run-script phpcs -- --standard=PSR12 routes

start:
	heroku local -f Procfile

setup:
	composer install
