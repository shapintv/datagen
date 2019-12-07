.PHONY: ${TARGETS}
.DEFAULT_GOAL := help

DIR := ${CURDIR}
QA_IMAGE := jakzal/phpqa:php7.3-alpine

define say =
    echo "$1"
endef

define say_red =
    echo "\033[31m$1\033[0m"
endef

define say_green =
    echo "\033[32m$1\033[0m"
endef

define say_yellow =
    echo "\033[33m$1\033[0m"
endef

help:
	@$(call say_yellow,"Usage:")
	@$(call say,"  make [command]")
	@$(call say,"")
	@$(call say_yellow,"Available commands:")
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%s\033[0m___%s\n", $$1, $$2}' | column -ts___

install: ## Install project
	@$(call say_green,"==\> Install Composer dependencies")
	@composer install -n

cs-lint: ## Verify check styles
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) php-cs-fixer fix --diff-format udiff --dry-run -vvv

cs-fix: ## Apply Check styles
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) php-cs-fixer fix --diff-format udiff -vvv

phpstan: ## Run PHPStan
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) phpstan analyse

test: cs-lint phpstan ## Launch tests
	@rm -rf ./tests/app/var ./tests/app/cache
	@$(call say_green,"==\> Launch unit tests")
	@vendor/bin/phpunit
