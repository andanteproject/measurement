.PHONY: help setup php tests cs-fixer phpstan ci-local coverage coverage-text clean

help: ## Show this help
	@echo "Available targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

setup: ## Setup PHP 8.1 environment
	docker compose build php
	docker compose run --rm php composer install

php: ## Open shell in PHP 8.1 container
	docker compose run --rm php /bin/bash

tests: ## Run tests on PHP 8.1
	docker compose run --rm php rm -rf tests/var/cache
	docker compose run --rm php mkdir -p tests/var/cache
	docker compose run --rm php vendor/bin/phpunit

cs-fixer: ## Run PHP-CS-Fixer
	docker compose run --rm php vendor/bin/php-cs-fixer fix --verbose

cs-fix: cs-fixer ## Alias for cs-fixer

phpstan: ## Run PHPStan
	docker compose run --rm php vendor/bin/phpstan analyse --memory-limit=512M

ci-local: ## Run GitHub Actions locally using act
	act -j build

coverage: ## Generate test coverage report (HTML + text summary)
	docker compose run --rm php vendor/bin/phpunit --coverage-html coverage --coverage-text

coverage-text: ## Show coverage summary in terminal only (fast)
	docker compose run --rm php vendor/bin/phpunit --coverage-text --colors=always

clean: ## Clean up generated files
	rm -rf vendor/ tests/var/ coverage/
	docker compose down -v
