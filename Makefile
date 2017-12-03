.PHONY: tests

install:
	@php bin/console --env=prod dbal:schema:update --force

tests:
	@php vendor/bin/phpspec run
	#@php vendor/bin/behat
