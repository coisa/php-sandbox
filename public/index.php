<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Application scope isolation
 */
call_user_func(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    $app = new Slim\App($container);

    $routes = require __DIR__ . '/../config/routes.php';
    $routes($app);

    $middlewares = require __DIR__ . '/../config/middlewares.php';
    $middlewares($app);

    chdir(dirname(__DIR__));

    return $app->run();
});