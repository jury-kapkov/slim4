<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\Views\Twig;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    'view' => function () {
        return Twig::create(__DIR__ . '/../resources/views',
//            ['cache' => __DIR__ . '/../cache']);
            ['cache' => false]);
    },

    'db' => function(ContainerInterface $container) {

        $config = $container->get('settings')['db_settings'];

        $host = $config['host'];
        $password = $config['password'];
        $charset = $config['charset'];
        $username = $config['username'];
        $database = $config['database'];
        $port = $config['port'];

        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ];

        $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $database . ";charset=" . $charset . ";";

        return new PDO($dsn, $username, $password, $opt);
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details']
        );
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

];