cs:
	./vendor/fabpot/php-cs-fixer/php-cs-fixer fix src/ --verbose --config-file=.php_cs

cs_dry_run:
	./vendor/fabpot/php-cs-fixer/php-cs-fixer fix --verbose --dry-run --config-file=.php_cs

db_create:
	 php app/console doctrine:database:create

db_update:
	 php app/console doctrine:schema:update --force

create_user:
	php app/console fos:user:create admin admin@sportroops.dev admin

promote_user:
	php app/console fos:user:promote admin ROLE_ADMIN

test:
	phpunit
