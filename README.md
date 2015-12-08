Sportroops
=============

Symfony project created on December 8, 2015, 5:28 pm.

## Getting started

- Clone repository :
    `git clone git@git.sutunam.com:sluaire/sportroops-com`

- Install vendors :
    `cd sportroops-com && php composer.phar install`

- Use dev branch :
    `git checkout dev`

-  Create database :
    `make db_create`

- Dump database schema :
    `make schema_update`

- Create sonata-admin user
    `make create_user`

- Add permissions to your user
    `make promote_user`

Now you can sign-in using admin/admin as username/password.

# Contributing

- Before push your local changes :
    `git pull` (keep your local repository up-to-date from HEAD)

- Before merge branch into master OR commit a big feature:
    `make cs` (keep code on top of PSR standards)

- After pull on prod environment
    `make db_update && sfcl`
