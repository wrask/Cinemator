include .env
export

up: ## Start up local environment
	@echo "starting ..."
	docker-compose up -d

down: ## Stop up local environment
	@echo "stopping ..."
	docker-compose down

build: ## Build and start up local environment
	@echo "building ..."
	docker-compose up --build -d


artisan: ## Run an artisan command
	@echo "run artisan ..."
	docker exec -it $$APP_CONTAINER php artisan $(command)

mc: ## Make a controller
	@echo "creating a controller ..."
	docker exec -it $$APP_CONTAINER php artisan make:controller $(name)

tests: ## Run all tests
	@echo "running tests ..."
	docker exec -it $$APP_CONTAINER php artisan test

unit: ## Run unit tests
	@echo "running unit tests ..."
	docker exec -it $$APP_CONTAINER ./vendor/bin/phpunit tests/Unit


feature: ## Run feature tests
	@echo "running feature tests ..."
	docker exec -it $$APP_CONTAINER ./vendor/bin/phpunit tests/Feature

migrate: ## Start database migrations
	@echo "running db migrations ..."
	docker exec $$APP_CONTAINER artisan migrate

me: ## Enable maintenance mode
	@echo "enabling the maintenance mode ..."
	docker exec $$APP_CONTAINER artisan down
##	docker exec app app/artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"

md: ## Disable maintenance mode
	@echo "disabling the maintenance mode ..."
	docker exec $$APP_CONTAINER artisan up

composer: ## Run composer command
	docker exec $$APP_CONTAINER composer $(command)

help:
	@grep -E '^[a-zA-Z_0-9-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help

