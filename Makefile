## Start up local environment
up:
	@echo "starting ..."
	docker-compose up -d

## Stop up local environment
down:
	@echo "stopping ..."
	docker-compose down

## Build and Start up local environment
build:
	@echo "building ..."
	docker-compose up --build -d


## Start feature tests
tests:
	@echo "running feature tests ..."
	docker exec app /var/www/app/vendor/phpunit/phpunit/phpunit /var/www/app/tests/Feature

help:
	@grep -E '^[a-zA-Z_0-9-]+:.*?## .*$$' makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help

