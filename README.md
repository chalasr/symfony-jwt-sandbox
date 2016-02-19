Symfony REST Skeleton
=====================

Ready-to-use application built on top of the Symfony fullstack and best

What's inside ?
----

- **[FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)** - *REST* routing & rendering
- **[FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)** - User provider
- **[LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)** - Authentication handling
- **[JMSSerializerBundle](https://github.com/schmittjoh/JMSSerializerBundle)** - Object serialization
- **[NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle)** - Easy API documentation

> Provides a built-in user management including authentication through JSON Web Token.

Installation
--------------

Open a command console and execute the following command to download the latest stable version of this project:

```bash
$ composer create-project chalasr/symfony-rest-skeleton --minimum-stability=dev path/to/install
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Usage
-----

Available routes :

##### Security

- `POST /v1/register`
- `POST /v1/login`
- `POST /v1/oauth/login`
- `GET /v1/guest/login`
- `POST /v1/reset_password`

##### User

- `GET /v1/users`
- `GET /v1/users/{id}`

> _NOTE:_ Resources are serialized in `JSON` as default format, but

License
-------

The code is released under the business-friendly GPL-3.0 license.

For the whole copyright, see the [LICENSE](LICENSE) file.

Contributing
------------

See the guidelines in the  [CONTRIBUTING](https://github.com/chalasr/symfony-rest-edition/blob/master/CONTRIBUTING.md) file.
