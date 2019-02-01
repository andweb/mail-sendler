#!/bin/sh

cp docker-compose.yml.example docker-compose.yml

docker-compose up -d --build

docker-compose exec --user=www-data php composer install -d /var/www/mail-sendler
