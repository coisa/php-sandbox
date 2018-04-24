<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'Auth0\SDK\Auth0' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['auth0'];

        return new Auth0\SDK\Auth0($settings);
    }
];
