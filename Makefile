cs:
	./vendor/fabpot/php-cs-fixer/php-cs-fixer fix src/ --verbose --config-file=.php_cs

cs_dry_run:
	./vendor/fabpot/php-cs-fixer/php-cs-fixer fix --verbose --dry-run --config-file=.php_cs

db_create:
	 php app/console doctrine:database:create

db_update:
	 php app/console doctrine:schema:update --force

create_user:
	php app/console fos:user:create admin@sutunam.com admin@sutunam.com admin

promote_user:
	php app/console fos:user:promote admin@sutunam.com ROLE_ADMIN

create_guest_user:
	php app/console fos:user:create guest@sportroops.fr guest@sportroops.fr guest
	php app/console fos:user:promote guest@sportroops.fr ROLE_GUEST

test:
	phpunit
