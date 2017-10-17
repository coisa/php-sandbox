<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'renderer' => function (ContainerInterface $container) {
        return $container->get('plates');
    },
    'plates' => function (ContainerInterface $container) {
        $engine = $container->get('plates.engine');

        $engine->loadExtensions([
            $container->get('plates.asset'),
            $container->get('plates.url')
        ]);

        return $engine;
    },
    'plates.engine' => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        return new League\Plates\Engine($settings['plates']['path'], $settings['plates']['extension']);
    },
    'plates.asset' => function (ContainerInterface $container) {
        return new League\Plates\Extension\Asset(dirname(dirname(__DIR__)) . '/public');
    },
    'plates.url' => function () {
        return new League\Plates\Extension\URI($_SERVER['PATH_INFO']);
    },
];