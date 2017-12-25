.PHONY: tests

install:
	@composer install -n
	@php bin/console dbal:schema:update --force

tests:
	@php vendor/bin/phpspec run
	@php vendor/bin/behat -s domain

cs_fix:
	@php vendor/bin/php-cs-fixer fix
