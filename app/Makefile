.DEFAULT_GOAL:=help

.PHONY: install
install:
	composer install --no-interaction --no-suggest --ansi

.PHONY: test
test:
	vendor/bin/phpunit --testdox --exclude-group=none --colors=always

.PHONY: cs
cs:
	vendor/bin/php-cs-fixer fix --no-interaction --diff --verbose

.PHONY: stan
stan:
	vendor/bin/phpstan analyse --memory-limit=-1
