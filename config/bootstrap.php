<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';



$containerBuilder = new ContainerBuilder();

// Set up settings
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

// Build PHP-DI Container instance
$container = $containerBuilder->build();


//DB
//$container->set('db', function(ContainerInterface $container){
//
//    $config = $container->get('settings')['db_settings'];
//
//    $host = $config->host;
//    $password = $config->password;
//    $charset = $config->charset;
//    $username = $config->username;
//    $database = $config->database;
//    $port = $config->port;
//
//    $opt = [
//        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
//    ];
//
//    $dsn = "mysql:host=".$host.";port=".$port.";dbname=".$database.";charset=".$charset.";";
//
//    return new PDO($dsn, $username, $password, $opt);
//});

// Create App instance
$app = $container->get(App::class);

// Register routes
(require __DIR__ . '/../routes/web.php')($app);

// Register middleware
(require __DIR__ . '/middleware.php')($app);

return $app;