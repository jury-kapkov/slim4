<?php

use Slim\App;

return function (App $app) {
    $app->get('/', 'App\Controllers\IndexController:index')->setName('index');
};