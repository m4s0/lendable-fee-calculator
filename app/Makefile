.DEFAULT_GOAL:=help

.PHONY: install
install:
	composer install --no-interaction --no-suggest --ansi

.PHONY: test
test:
	vendor/bin/phpunit --testdox --exclude-group=none --colors=always
