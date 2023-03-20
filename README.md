# Приложение "Инвентаризируй это"

### Как поднять приложение
1. Зайдите в директорию с приложением и создайте docker-compose.override.yml: ```cp docker-compose.override.yml.dist docker-compose.override.yml```
2. В docker-compose.override.yml установите значения внешних портов и дефолтные доступы для БД.
3. Запустите сборку проекта: ```make build-up```
4. Приложение будет доступно по порту, который указан в docker-compose.override.yml
5. Создайте конфигурационный файл для миграций ```cp migrations-db.php.dist migrations-db.php``` и пропишите в него доступы для БД
6. Запустите миграции ```vendor/bin/doctrine-migrations migrations:migrate```