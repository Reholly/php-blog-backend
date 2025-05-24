# php-blog-backend

1. Создание миграции (поднять перед этим в композе, дать права на изменение файла)
```bash
   docker compose exec app php artisan make:migration create_articles_table --create=articles_init

```
2. Применение
```bash
docker compose exec php-fpm php artisan migrate
```

3. Применение сидера
```bash
docker compose exec php-fpm php artisan db:seed
```
