.PHONY: ${TARGETS}
.DEFAULT_GOAL := help

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

cs-fix: ## Fix coding standard
	@vendor/bin/php-cs-fixer fix

cs-lint: ## Lint php code source
	@$(call say_green,"==\> Check style")
	@vendor/bin/php-cs-fixer fix --dry-run --diff --no-interaction -v

phpstan: ## Run PHPStan
	@vendor/bin/phpstan analyze -c phpstan.neon -l max src/ tests/

test: cs-lint phpstan ## Launch tests
	@rm -rf ./tests/app/var
	@$(call say_green,"==\> Launch unit tests")
	@vendor/bin/phpunit
