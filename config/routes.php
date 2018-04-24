<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Define application routes
 */
return function (App\Web\Application $app) {
    $router = $app->getRouter();
    $container = $app->getContainer();

    $engine = $container->get(League\Plates\Engine::class);
    $router->get('/', new App\Web\Action\IndexAction($engine));

    $auth0 = $container->get(Auth0\SDK\Auth0::class);
    $router->get('/login', new App\Web\Action\User\LoginAction($auth0));
    $router->get('/logout', new App\Web\Action\User\LogoutAction($auth0));
};
