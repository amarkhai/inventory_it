# Приложение "Инвентаризируй это"

### Как поднять приложение
1. Зайдите в директорию с приложением и создайте файл .env: ```cp .env.example .env```
2. Настройте файл .env под себя.
3. Запустите сборку проекта: ```make build-up``` - будут созданы необходимые контейнеры и установлены пакеты composer.
4. Приложение будет доступно по порту, который указан в переменной HTTP_EXPO_PORT в .env.
5. Запустите миграции ```docker-compose exec php-fpm vendor/bin/doctrine-migrations migrations:migrate```
