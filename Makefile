.PHONY: tests

install:
	@composer install -n
	@php bin/console cache:clear
	@php bin/console dbal:schema:update --force

build:
	@php bin/console cache:clear --no-debug --no-warmup
	@php bin/console cache:warmup
	@node node_modules/.bin/encore production

tests:
	@php vendor/bin/phpspec run
	@php vendor/bin/behat -s domain

cs_fix:
	@php vendor/bin/php-cs-fixer fix
