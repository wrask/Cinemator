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

## Make controller
mc:
	docker-compose exec -it app php app/artisan make:controller $(controller_name)

help:
	@grep -E '^[a-zA-Z_0-9-]+:.*?## .*$$' makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help

