<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'session' => function (ContainerInterface $container) {
        if (session_status() === PHP_SESSION_DISABLED) {
            throw new \RuntimeException('Enable your PHP Session Module');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();

            // @TODO add some configuration with default values
        }

        return $_SESSION;
    }
];