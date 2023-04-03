sudo cp .deploy/nginx/nginx.conf /etc/nginx/conf.d/inventory_it.conf
sudo sed -i -- "s|%SERVER_NAME%|$1|g" /etc/nginx/conf.d/inventory_it.conf
sudo service nginx restart
sudo -u www-data composer i
sudo service php8.1-fpm restart

# здесь формируем .env файл
echo DB_DRIVER=$2 | sudo -u www-data tee -a .env > /dev/null
echo DB_USER=$3 | sudo -u www-data tee -a .env > /dev/null
echo DB_PASSWORD=$4 | sudo -u www-data tee -a .env > /dev/null
echo DB_NAME=$5 | sudo -u www-data tee -a .env > /dev/null
echo DB_HOST=$6 | sudo -u www-data tee -a .env > /dev/null
echo DB_PORT=$7 | sudo -u www-data tee -a .env > /dev/null
echo DB_DSN=$8 | sudo -u www-data tee -a .env > /dev/null
echo JWT_TOKEN_EXPIRATION_TIME=$9 | sudo -u www-data tee -a .env > /dev/null
echo JWT_SECRET=${10} | sudo -u www-data tee -a .env > /dev/null

sudo -u www-data vendor/bin/doctrine-migrations migrations:migrate --no-interaction