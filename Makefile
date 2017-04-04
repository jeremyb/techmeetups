.PHONY: tests

tests:
	@php vendor/bin/phpspec run
	@php vendor/bin/behat -s domain
