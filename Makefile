#--- Makefile common
# COLORS
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
WHITE  := $(shell tput -Txterm setaf 7)
RESET  := $(shell tput -Txterm sgr0)

TARGET_MAX_CHAR_NUM=20
## show help
help:
	@echo ''
	@echo 'Usage:'
	@echo '  ${YELLOW}make${RESET} ${GREEN}<target>${RESET}'
	@echo ''
	@echo 'Targets:'
	@awk '/^[a-zA-Z\-\_0-9]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")-1); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "  ${YELLOW}%-$(TARGET_MAX_CHAR_NUM)s${RESET} ${GREEN}%s${RESET}\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)
.DEFAULT_GOAL := help
#--- /end of Makefile common

####
## globals
########################
DOCKER_COMPOSE  := docker-compose
EXEC_PHP        := docker exec -it dotted_php
COMPOSER        := $(EXEC_PHP) composer

####
## docker-compose tasks
########################
## pull and build
build:
	$(DOCKER_COMPOSE) build --pull
## pull and build with no cache
build-nocache:
	$(DOCKER_COMPOSE) build --pull --no-cache
## up and remove orphans and avoid recreate
start:
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate
## stop
stop:
	$(DOCKER_COMPOSE) stop
## kill delete all with volumes
kill:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans
## login to php container
php:
	$(EXEC_PHP) /bin/bash

.PHONY: build build-nocache kill start stop php

####
## rules based on files
########################
vendor: composer.lock
	$(COMPOSER) update --lock --no-scripts --no-interaction --ansi
composer.lock: composer.json
	$(COMPOSER) install --ansi

####
## quality: lint yaml/twig | php csfixer/phpstan
########################
## quality: php quality stuff
quality: validate-composer phpunit phpcs-check phpstan
## Validate composer.json and composer.lock
validate-composer: vendor
	$(COMPOSER) validate
## quality: php-cs-fixer dry run
phpcs-check: vendor
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --dry-run --diff --ansi --using-cache=no --allow-risky=yes
## quality: php-cs-fixer fix
phpcs: vendor
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --ansi --using-cache=no --allow-risky=yes
## quality: phpstan
phpstan: vendor
	$(EXEC_PHP) ./vendor/bin/phpstan analyse --ansi
## phpunit : run all tests
phpunit: vendor
	$(EXEC_PHP) ./vendor/bin/phpunit

.PHONY: phpunit quality phpcs-check phpcs phpstan
