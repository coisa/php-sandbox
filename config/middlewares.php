<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\App;

/**
 * Define application middlewares
 */
return function (App $app) {
    $container = $app->getContainer();

    $app->add($container->get(Slim\HttpCache\Cache::class));
};
