<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'session' => function (ContainerInterface $container) {
        if (session_status() === PHP_SESSION_DISABLED) {
            throw new \RuntimeException('Enable your PHP Session Module');
        }

        return $container->get('aura.session');
    },
    'aura.session' => function (ContainerInterface $container) {
        $factory = new Aura\Session\SessionFactory;

        return $factory->newInstance($_COOKIE);
    }
];