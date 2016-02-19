cs:
	./vendor/fabpot/php-cs-fixer/php-cs-fixer fix src/ --verbose --config-file=.php_cs

cs_dry_run:
	./vendor/fabpot/php-cs-fixer/php-cs-fixer fix --verbose --dry-run --config-file=.php_cs

create_admin_user:
	php app/console fos:user:create --super-admin admin@rch.fr admin@rch.fr admin

create_guest_user:
	php app/console fos:user:create guest@sportroops.fr guest@sportroops.fr guest
	php app/console fos:user:promote guest@sportroops.fr ROLE_GUEST

test:
	phpunit
