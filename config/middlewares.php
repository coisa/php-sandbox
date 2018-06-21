<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Define application middlewares
 */
return function (App\Web\Application $app) {
    $router = $app->getRouter();
    $container = $app->getContainer();

    $router->add($container->get(Slim\HttpCache\Cache::class));

    // NotFoundHandler
    // ErrorHandler
    // Authorization
    // Routing
    //$app->addMiddleware(ErrorHandlerMiddleware::class);
    //$app->addMiddleware(new AcceptHeaderDecorator([
    //  'application/html' => PlatesMiddleware::class,
    //  'application/json' => JsonMiddleware::class
    //]));
    //$app->addMiddleware(RouteMiddleware::class);
    //$app->addMiddleware(CsrfMiddleware::class);
    //$app->addMiddleware(NotFoundHandler::class);
};
