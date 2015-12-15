# Security #

### `POST` /v1/login_check ###

_Authenticate User by email/password._

Authenticate User by email/password.

#### Requirements ####

**_format**

  - Requirement: json

#### Parameters ####

email:

  * type: string
  * required: true
  * description: Email

password:

  * type: string
  * required: true
  * description: Password


### `POST` /v1/oauth/login ###

_Register/login user from OAuth._

Register/login user from OAuth.

#### Requirements ####

**_format**

  - Requirement: json

#### Parameters ####

id:

  * type: integer
  * required: true
  * description: Facebook ID

name:

  * type: string
  * required: true
  * description: Username

email:

  * type: string
  * required: true
  * description: Email credential

first_name:

  * type: string
  * required: false
  * description: Firstname

last_name:

  * type: string
  * required: false
  * description: Lastname


### `POST` /v1/signup ###

_Register new user account._

Register new user account.

#### Requirements ####

**_format**

  - Requirement: json

#### Parameters ####

email:

  * type: string
  * required: true
  * description: Email

password:

  * type: string
  * required: true
  * description: Password

first_name:

  * type: string
  * required: false
  * description: First name

last_name:

  * type: string
  * required: false
  * description: Last name



# User #

## /v1/users ##

### `GET` /v1/users ###

_Get list of users._

Get list of users.

#### Requirements ####

**_format**

  - Requirement: json
