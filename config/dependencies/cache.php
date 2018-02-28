<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'Slim\HttpCache\Cache' => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        return new Slim\HttpCache\Cache($settings['cache']['type'], $settings['cache']['maxAge']);
    },
    'Slim\HttpCache\CacheProvider' => function (ContainerInterface $container) {
        return new Slim\HttpCache\CacheProvider();
    }
];
