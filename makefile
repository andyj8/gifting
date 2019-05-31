testAll:
	@make testPhpUnit
	@make testBehat

testPhpUnit:
	@php ./vendor/bin/phpunit --config test/phpunit/phpunit.xml

testBehat:
	@php ./vendor/bin/behat --config test/behat/behat.yml
