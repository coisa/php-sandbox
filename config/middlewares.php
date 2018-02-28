<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\App;

/**
 * Define application middlewares
 */
return function (App $app) {
    $container = $app->getContainer();

    /** @var Zeuxisoo\Whoops\Provider\Slim\WhoopsErrorHandler $errorHandler */
    $errorHandler = $container->get(Zeuxisoo\Whoops\Provider\Slim\WhoopsErrorHandler::class);

    /** @var Slim\HttpCache\Cache $httpCache */
    $httpCache = $container->get(Slim\HttpCache\Cache::class);

    $app->add($errorHandler);
    $app->add($httpCache);
};