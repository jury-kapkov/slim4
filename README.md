# slim4 - тестовое задание

В проекте не создавались миграции. База создавалась вручную через PhpMyAdmin. sql файл с базой данных ледит в корне репозитория.


## Для установки зависимостей запустит:

```
composer install
```

## Настройка подключения к бд

Перейдите в файл settings.php и укажите Ваши данные для подключения к бд:

```php
$settings['db_settings'] = [
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'session',
    'username'  => 'mysql',
    'password'  => 'mysql',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'port'      => '3306',
];
```

## Для запуска сервера перейдите в папку public и запустите команду:

```
php -S localhost:8000
```
