# BOILERPLATE API-REST LARAVEL

## Table of contents

- [Installation](#installation)
- [License](#license)

### Installation docker environment and start project

```sh
git archive --remote=git@github.com:cirelramos/boilerplate-api-laravel.git HEAD install.sh | tar -x  && sh install.sh
```

### start only the container project

```sh
sudo docker run -d --rm --hostname=${PWD##*/}  --name=${PWD##*/} \
--network="boilerplate_boilerplate-network"  \
--volume $PWD:/var/www -e HRROLE=backend \
-p 8780:80 \
cirelramos/laravel-supervisor
```

### start only the container project horizon mode

```sh
cd boilerplate 
sudo docker run -d --rm --hostname=${PWD##*/}  --name=${PWD##*/} \
--network="boilerplate_boilerplate-network"  \
--volume $PWD:/var/www -e HRROLE=backend-horizon \
-p 8780:80 \
cirelramos/laravel-supervisor
```




### see query container mysql

```sh
sudo docker exec -it container-mysql-boilerplate tail -f /var/log/mysql/query.log
```

### Integration with other Backend
add host and key to base 64 in .env

Example:
```sh
HOST_KEY_EXTERNAL_ACCESS=localhost:3127,KmxecbdMv83rgznzD5lq4PIqRJcsDGoH|container-rapi-gateway,KmxecbdMv83rgznzD5lq4PIqRJcsDGo
```


### License

Cirel Boilerplate is released under the MIT Licence. See the bundled [LICENSE](LICENSE.md) file for details.
