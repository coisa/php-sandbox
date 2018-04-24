<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Define application middlewares
 */
return function (App\Web\Application $app) {
    $router = $app->getRouter();
    $container = $app->getContainer();

    $router->add($container->get(Slim\HttpCache\Cache::class));
};
