echo "PURGE PROJECT" && \
sudo rm -rf boilerplate && \
sudo docker rm -f container-mysql-boilerplate > /dev/null && \
sudo docker rm -f container-redis-boilerplate > /dev/null && \
sudo docker rm -f boilerplate > /dev/null && \
echo "CLONE PROJECT" && \
git clone git@github.com:cirelramos/boilerplate-api-laravel.git && \
cd boilerplate && \
echo "CREATE NETWORK AND CONTAINERS MYSQL AND REDIS" && \
sudo docker-compose up -d && \
cp .env.example .env && \
sleep 30s && \
echo  "DROP DATABASE IF EXISTS boilerplate;" | sudo docker exec -i container-mysql-boilerplate mysql -uroot -proot && \
sleep 2s && \
echo "CREATE DATABASE boilerplate;" | sudo docker exec -i container-mysql-boilerplate mysql -uroot -proot && \
echo "BACKEND CONTAINER" && \
sudo docker run -d --rm --hostname=${PWD##*/}  --name=${PWD##*/} \
--network="boilerplate_boilerplate-network"  \
--volume $PWD:/var/www -e HRROLE=backend \
-p 8780:80 \
cirelramos/laravel-supervisor && \
echo "BACKEND COMMANDS" && \
sleep 60s && \
sudo docker exec -i boilerplate php artisan migrate  && \
sudo docker exec -i boilerplate php artisan key:generate && \
sudo docker exec -i boilerplate php artisan l5-swagger:generate && \
sudo chmod -R 777  storage/framework/
cd .. && \
sudo docker logs -f boilerplate
