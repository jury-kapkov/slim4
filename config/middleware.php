<?php

namespace App\Middlewares;

use Selective\BasePath\BasePathMiddleware;
use Slim\App;

use Slim\Middleware\ErrorMiddleware;
use Slim\Views\TwigMiddleware;


return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    $app->add(BasePathMiddleware::class);

    // Catch exceptions and errors
    $app->add(ErrorMiddleware::class);

    // Add Twig-View Middleware
    $app->add(TwigMiddleware::createFromContainer($app));

//     Мидлвар для проверки сессии
    $app->add(MySessionMiddleware::InitSessionMiddleware($app));

//    // Session middleware
//    $app->add(function (Request $request, Response $response, $next) {
//        $session = $this->get('session');
//        echo $session;
//        return $response;
//    });
};