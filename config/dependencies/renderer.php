<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'renderer' => function (ContainerInterface $container) {
        return $container->get(League\Plates\Engine::class);
    },
    'League\Plates\Engine' => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        $engine = new League\Plates\Engine($settings['plates']['path'], $settings['plates']['extension']);

        $engine->loadExtensions([
            $container->get(League\Plates\Extension\Asset::class),
            $container->get(League\Plates\Extension\URI::class)
        ]);

        return $engine;
    },
    'League\Plates\Extension\Asset' => function (ContainerInterface $container) {
        return new League\Plates\Extension\Asset(dirname(dirname(__DIR__)) . '/public');
    },
    'League\Plates\Extension\URI' => function () {
        return new League\Plates\Extension\URI($_SERVER['PATH_INFO']);
    },
];
