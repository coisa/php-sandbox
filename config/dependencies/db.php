<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'db' => function (ContainerInterface $container) {
        return $container->get('spot.locator');
    },
    'spot.locator' => function (ContainerInterface $container) {
        $config = $container->get('spot.config');

        return new Spot\Locator($config);
    },
    'spot.config' => function (ContainerInterface $container) {
        $config = new Spot\Config();
        $settings = $container->get('settings');

        if (!empty($settings['db'])) {
            foreach ($settings['db'] as $name => $dsn) {
                $config->addConnection($name, $dsn);
            }
        }

        return $config;
    }
];
