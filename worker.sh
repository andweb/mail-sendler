#!/bin/sh

docker-compose exec --user=www-data php php /var/www/mail-sendler/public/worker/worker.php