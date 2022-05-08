# BOILERPLATE API-REST LARAVEL



## Table of contents

- [Installation](#installation)
- [License](#license)

## Installation

```sh
cp .env.example .env
```

```sh
composer install
```

```sh
php artisan key:generate
```

```sh
php artisan passport:install
```


## Integration with other Backend


add host and key to base 64 in .env

Example:
```sh
HOST_KEY_EXTERNAL_ACCESS=localhost:3127,KmxecbdMv83rgznzD5lq4PIqRJcsDGoH|container-rapi-gateway,KmxecbdMv83rgznzD5lq4PIqRJcsDGo
```

## RUN WITH DOCKER

```sh
sudo docker run -d --rm --hostname=${PWD##*/}  --name=${PWD##*/} \
 --volume $PWD:/var/www -e HRROLE=backend cirelramos/laravel-supervisor && sudo docker logs -f ${PWD##*/}
```


## License

Litermi Boilerplate is released under the MIT Licence. See the bundled [LICENSE](LICENSE.md) file for details.
