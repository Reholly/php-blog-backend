<?php
// migrate.php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

$container = new Container();
Facade::setFacadeApplication($container);

// Настройка подключения к БД
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'pgsql',
    'host'      => 'localhost',
    'port'      => 9081,
    'database'  => 'blog',
    'username'  => 'admin',
    'password'  => 'admin',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container->singleton('db', function () use ($capsule) {
    return $capsule->getDatabaseManager();
});

// Регистрируем builder схемы (используется в миграциях)
$container->singleton('db.schema', function () use ($capsule) {
    return $capsule->getDatabaseManager()->getSchemaBuilder();
});

// Репозиторий миграций (таблица migrations)
$repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');

// Если таблицы migrations нет — создаем её
if (!$repository->repositoryExists()) {
    $repository->createRepository();
}

// Настройка мигратора
$migrator = new Migrator($repository, $capsule->getDatabaseManager(), new Filesystem());

// Путь к папке с миграциями
$migrationsPath = __DIR__ . '/database/migrations';

// Загружаем миграции из файлов
$migrations = $migrator->getMigrationFiles($migrationsPath);

// Запускаем миграции
if (empty($migrations)) {
    echo "Нет миграций для выполнения.\n";
    exit;
}

$migrator->run($migrationsPath);

// Выводим результат
foreach ($migrator->getNotes() as $note) {
    echo $note . "\n";
}

echo "Все миграции выполнены!\n";
