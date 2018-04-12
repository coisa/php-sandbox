<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'Aura\Session\Session' => function (ContainerInterface $container) {
        if (session_status() === PHP_SESSION_DISABLED) {
            throw new \RuntimeException('Enable your PHP Session Module');
        }

        $factory = new Aura\Session\SessionFactory;

        return $factory->newInstance($_COOKIE);
    }
];
