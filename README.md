# Приложение "Инвентаризируй это"

### Как поднять приложение
1. Зайдите в директорию с приложением и создайте docker-compose.override.yml: ```cp docker-compose.override.yml.dist docker-compose.override.yml```
2. В docker-compose.override.yml установите значения внешних портов и дефолтные доступы для БД.
3. Запустите сборку проекта: ```make build-up```
4. Приложение будет доступно по порту, который указан в docker-compose.override.yml