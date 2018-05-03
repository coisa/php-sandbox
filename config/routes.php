<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Web\Action;

/**
 * Define application routes
 */
return function (App\Web\Application $app) {
    $router = $app->getRouter();
    $container = $app->getContainer();

    $negotiator = new Negotiation\Negotiator();
    $priorities = [
        'text/html; charset=UTF-8',
        'application/json; charset=UTF-8'
    ];

    /** @var \Psr\Http\Message\RequestInterface $request */
    $request = $container->get('request');
    $accepts = $request->getHeaderLine('Accept');

    $mediaType = $negotiator->getBest($accepts, $priorities);

    if (!$mediaType) {
        // Accept Http Error
    }

    switch ($mediaType->getType()) {
        case 'text/html':
            break;
    }

    /*
     // check accepts here?!
     new IndexAction(new PlatesResponder($engine), $container);
    */

    $engine = $container->get(League\Plates\Engine::class);
    $router->get('/', new Action\IndexAction($engine))->setName('home');

    $auth0 = $container->get(Auth0\SDK\Auth0::class);
    $router->get('/login', new Action\User\LoginAction($auth0));
    $router->get('/logout', new Action\User\LogoutAction($auth0));
};
