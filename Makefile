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

#--- globals
DOCKER_COMPOSE  := docker-compose
EXEC_PHP        := $(DOCKER_COMPOSE) exec -T php
COMPOSER        := $(EXEC_PHP) composer
EXEC_PHP_SHELL  := $(DOCKER_COMPOSE) exec php
#--- /end of globals

#--- docker-compose tasks
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

.PHONY: build build-nocache kill start stop
#--- end of docker-compose tasks

#--- php tasks
## login to php container
php:
	$(EXEC_PHP_SHELL)
composer.lock: composer.json
	$(COMPOSER) update --lock --no-scripts --no-interaction
vendor: composer.lock
	$(COMPOSER) install
#--- end of php tasks
