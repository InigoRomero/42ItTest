# USAGE

```shell
mv envEXAMPLE .env
Fill .env with your credentials
Docker-compose build
Docker-compose up -d
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache
```
Then open http://localhost:3000/ :)

