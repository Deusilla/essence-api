# Essence Api

## Install & use

### Before install
* install [Docker](https://docs.docker.com/engine/install/)
* install [Lando](https://docs.lando.dev/basics/installation.html)

### Run services

``` sh
$ lando start
$ lando composer install
$ lando console doctrine:migrations:migrate
```

### Stop

```
$ lando poweroff
```

### Rebuild

``` sh
$ lando rebuild -y
```

## Testing

``` sh
$ lando console doctrine:schema:create --env=test
$ lando test
```


## TODO:

- Fix AbstractEnumType for exclude relicts on migration generate.