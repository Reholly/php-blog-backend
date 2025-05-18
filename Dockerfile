# Используем официальный образ PHP 8.2
FROM php:8.2-fpm

# Установка необходимых зависимостей и инструментов
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_pgsql

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Определите рабочую папку
WORKDIR /var/www

# Копируем файлы проекта в контейнер
COPY . .
RUN mkdir -p storage/framework/views

# Устанавливаем зависимости
RUN composer install

RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
