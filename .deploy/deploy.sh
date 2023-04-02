sudo cp nginx/nginx.conf /etc/nginx/conf.d/inventory_it.conf
sudo sed -i -- "s|%SERVER_NAME%|$SERVER_NAME|g" /etc/nginx/conf.d/inventory_it.conf
sudo service nginx restart
cd .. && sudo -u www-data composer i
sudo service php8.1-fpm restart

# здесь формируем .env файл
echo "$DB_DRIVER" > .env
echo "$DB_USER" > .env
echo "$DB_PASSWORD" > .env
echo "$DB_NAME" > .env
echo "$DB_HOST" > .env
echo "$DB_PORT" > .env
echo "$DB_DSN" > .env
echo "$JWT_TOKEN_EXPIRATION_TIME" > .env
echo "$JWT_SECRET" > .env

sudo -u www-data ../vendor/bin/doctrine-migrations d:m:m --no-interaction