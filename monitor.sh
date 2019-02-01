#!/bin/sh

mkdir ./code/public/monitor
git clone https://github.com/yugene/Gearman-Monitor.git ./code/public/monitor
docker-compose exec --user=www-data php composer install -d /var/www/mail-sendler/public/monitor
docker cp "./docker/gearman/monitor/_config.php.example" $(docker-compose ps -q php):/var/www/mail-sendler/public/monitor/_config.php

