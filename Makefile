# Every command runs inside Docker. PHP and Composer are not needed on the host.

DOCKER_UID ?= $(shell id -u)
DOCKER_GID ?= $(shell id -g)
export DOCKER_UID DOCKER_GID

# Local demo login for the admin. Do not use these values anywhere else.
ADMIN_USER ?= admin
ADMIN_PASSWORD ?= admin
BUNDLES = OpenDxpAdminBundle,OpenDxpTinymceBundle,OpenDxpSeoBundle

COMPOSE = docker compose
PHP = $(COMPOSE) exec -T php
TEST_PHP = $(COMPOSE) run --rm -T test-php

.DEFAULT_GOAL := help
.PHONY: help setup up down vendor install demo assets watch console cache cs stan test

help: ## Show this help
	@grep -E '^[a-z-]+:.*## ' $(MAKEFILE_LIST) | awk -F':.*## ' '{printf "  make %-8s %s\n", $$1, $$2}'

setup: up vendor install demo assets ## First start: containers, packages, installation, demo content, styles

up: ## Start the containers
	$(COMPOSE) up -d

down: ## Stop the containers
	$(COMPOSE) down

vendor: ## Install the Composer packages
	$(PHP) composer install

install: ## Install OpenDXP into the database
	$(PHP) vendor/bin/opendxp-install -n \
		--admin-username=$(ADMIN_USER) --admin-password=$(ADMIN_PASSWORD) \
		--install-bundles=$(BUNDLES)

demo: ## Create or update the demo content
	$(PHP) bin/console app:demo:install

assets: ## Compile the SCSS
	$(PHP) bin/console sass:build

watch: ## Compile the SCSS on every change
	$(PHP) bin/console sass:build --watch

console: ## Run a console command, e.g. make console c=debug:router
	$(PHP) bin/console $(c)

cache: ## Clear the caches
	$(PHP) bin/console cache:clear
	$(PHP) bin/console opendxp:cache:clear

cs: ## Fix the code style
	$(PHP) vendor/bin/php-cs-fixer fix

stan: ## Run the static analysis
	$(TEST_PHP) vendor/bin/opendxp-test analyse .

test: ## Run the tests
	$(COMPOSE) run --rm -T --user=root test-php chown -R $(DOCKER_UID):$(DOCKER_GID) var/ public/var/
	$(TEST_PHP) vendor/bin/opendxp-test install
	$(TEST_PHP) bin/console sass:build
	$(TEST_PHP) vendor/bin/pest
