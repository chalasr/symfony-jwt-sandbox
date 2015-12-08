cs:
	./vendor/fabpot/php-cs-fixer/php-cs-fixer fix src/ --verbose --config-file=.php_cs

cs_dry_run:
	./vendor/fabpot/php-cs-fixer/php-cs-fixer fix --verbose --dry-run --config-file=.php_cs

schema:
	 php app/console doctrine:schema:update --force

test:
	phpunit
