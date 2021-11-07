include .env
export

## Start up local environment
up:
	@echo "starting ..."
	docker-compose up -d

## Stop up local environment
down:
	@echo "stopping ..."
	docker-compose down

## Build and start up local environment
build:
	@echo "building ..."
	docker-compose up --build -d


## Make a controller
mc:
	@echo "creating a controller ..."
	docker ecec $$APP_CONTAINER php app/artisan make:controller $(controller_name)

## Start feature tests
tests:
	@echo "running feature tests ..."
	docker exec app /var/www/app/vendor/phpunit/phpunit/phpunit /var/www/app/tests/Feature

## Start db migrations
migrate:
	@echo "running db migrations ..."
	docker exec $$APP_CONTAINER app/artisan migrate

## Enable maintenance mode
me:
	@echo "enabling the maintenance mode ..."
	docker exec $$APP_CONTAINER app/artisan down
##	docker exec app app/artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"


## Disable maintenance mode
md:
	@echo "disabling the maintenance mode ..."
	docker exec $$APP_CONTAINER app/artisan up

## Composer
composer:
	docker exec $$APP_CONTAINER composer $(composer_command)

help:
	@grep -E '^[a-zA-Z_0-9-]+:.*?## .*$$' makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help

