# Makefile para proyecto con Docker

# Variables
DOCKER_COMPOSE = docker-compose
CONTAINER_PHP = php-app

.PHONY: help build up down restart composer-install exec-php test logs clean test test-unit test-integration test-coverage

help: ## Muestra esta ayuda y lista de comandos disponibles.
	@echo "Uso: make [target]"
	@echo ""
	@echo "Comandos disponibles:"
	@grep -E '^[a-zA-Z_-]+:.*##' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Construir las imágenes de Docker
	$(DOCKER_COMPOSE) build

up: ## Levantar los contenedores en segundo plano
	$(DOCKER_COMPOSE) up -d

down: ## Detener y eliminar contenedores
	$(DOCKER_COMPOSE) down

restart: ## Reiniciar todos los contenedores
	$(DOCKER_COMPOSE) restart

composer-install: ## Instalar dependencias de Composer dentro del contenedor PHP
	$(DOCKER_COMPOSE) run --rm php composer install

composer-require: ## Instala una dependencia de Composer (requiere que pases la variable PKG)
	$(DOCKER_COMPOSE) run --rm php composer require $(PKG)

composer-require-dev: ## Instala una dependencia de desarrollo (requiere que pases la variable PKG)
	$(DOCKER_COMPOSE) run --rm php composer require --dev $(PKG)

composer-dump-autoload: ## Regenera el autoload de Composer dentro del contenedor.
	$(DOCKER_COMPOSE) run --rm php composer dump-autoload

exec-php: ## Acceder al contenedor PHP (abrir una shell bash)
	$(DOCKER_COMPOSE) exec php bash

logs: ## Visualizar los logs de los contenedores en tiempo real
	$(DOCKER_COMPOSE) logs -f

clean: ## Limpiar volúmenes y contenedores detenidos
	$(DOCKER_COMPOSE) down -v

openapi: ## Genera la documentación OpenAPI.
	$(DOCKER_COMPOSE) run --rm php ./vendor/bin/openapi --bootstrap /var/www/html/src/OpenApiBootstrap.php --output /var/www/html/public/openapi.json /var/www/html/src --debug

test: ## Ejecuta todos los tests
	docker-compose run --rm php vendor/bin/phpunit

test-unit: ## Ejecuta solo los tests unitarios
	docker-compose run --rm php vendor/bin/phpunit --testsuite Unit

test-integration: ## Ejecuta solo los tests de integración
	docker-compose run --rm php vendor/bin/phpunit --testsuite Integration

test-coverage: ## Ejecuta los tests y genera un reporte de cobertura HTML
	docker-compose run --rm -e XDEBUG_MODE=coverage php vendor/bin/phpunit --coverage-html public/coverage --coverage-clover public/coverage/clover.xml --colors=always