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

lint:
	composer phpcs
    
setup:
	composer install
	cp -n .env.example .env
	php artisan key:gen --ansi
	touch database/database.sqlite || true
	php artisan migrate
	npm install
