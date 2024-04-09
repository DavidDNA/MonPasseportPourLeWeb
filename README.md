# Mon Passeport pour le Web

## Setup development environment

* Create the .env file for Docker (take from .env.example)
* Make .env Symfony (take from .env.example)
* Install PHP/JS dependencies

```
cd app
composer install (require composer / php)
npm i (require NodeJS)
```

* Run Symfony Encore

```
cd app
npm run watch
```

* Run docker-compose

```
docker-compose up
```

* Create the databse

```
# From the php container
php bin/console doctrine:migrations:migrate
```

## Deployment

```
cd deployment
npm run deploy
```

## Useful commands

These commands require the symfony runtime and php.

### Entities

Make an entity

```
php bin/console make:entity
```

### Users

Change a user password

```
php bin/console security:hash-password 'your_plain_password' 'App\Entity\Teacher'
```

### Database operations

Make a migration

```
php bin/console make:migration
```

Run a migration

```
php bin/console doctrine:migrations:migrate
```

Revert a migration

```
php bin/console doctrine:migrations:migrate prev
```

Check migrations status

```
php bin/console doctrine:migrations:status
```
