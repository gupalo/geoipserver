IMAGE=gupalo/geoipserver
CONTAINER_NAME=geoipserver
PORT=8000

.PHONY: all build run rm test composer enter help

all: build run
help: ## Show this help
	@egrep -h '\s##\s' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## docker build
	docker-compose -f docker/docker-compose.yaml build

run: ## docker run
	docker-compose -f docker/docker-compose.yaml up -d

rm: ## docker stop & rm
	docker-compose -f docker/docker-compose.yaml rm -fv

test: ## phpunit
	docker-compose -f docker/docker-compose.yaml exec app php ./vendor/bin/phpunit

composer: ## composer install
	docker run --rm -v $(CURDIR):/code/ composer:2 composer install --ignore-platform-reqs -n -d /code/

enter: ## enter container
	docker-compose -f docker/docker-compose.yaml exec app bash
