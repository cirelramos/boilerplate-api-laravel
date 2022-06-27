echo "PURGE PROJECT" && \
sudo rm -rf boilerplate-github && \
sudo docker rm -f container-mysql-boilerplate > /dev/null && \
sudo docker rm -f container-redis-boilerplate > /dev/null && \
sudo docker rm -f boilerplate > /dev/null && \
echo "CLONE PROJECT" && \
git clone git@github.com:cirelramos/boirleplate-api-laravel.git && \
cd boilerplate && \
echo "CREATE NETWORK AND CONTAINERS MYSQL AND REDIS" && \
sudo docker-compose up -d && \
cp .env.example .env && \
sleep 10s && \
echo "BACKEND CONTAINER" && \
sudo docker run -d --rm --hostname=boilerplate-github  --name=boilerplate-github \
--network="boilerplate_boilerplate-network"  \
--volume $PWD:/var/www -e HRROLE=backend \
cirelramos/laravel-supervisor && \
echo "BACKEND COMMANDS" && \
sleep 60s && \
sudo docker exec -i boilerplate-github php artisan migrate  && \
sudo docker exec -i boilerplate-github php artisan key:generate && \
sudo docker exec -i boilerplate-github php artisan l5-swagger:generate && \
sudo chmod -R 777  storage/framework/
cd .. && \
sudo docker logs -f boilerplate-github
