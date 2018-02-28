<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'League\Event\Emitter' => function (ContainerInterface $container) {
        return new League\Event\Emitter();
    },
];