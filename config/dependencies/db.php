<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'PDO' => function (ContainerInterface $container) {
        $settings = $container->get('settings');
        $config = $settings['db']['default'];

        return new PDO($config['dsn'], $config['user'], $config['password']);
    },
    'Doctrine\DBAL\Configuration' => function (ContainerInterface $container) {
        return new Doctrine\DBAL\Configuration();
    },
    'Doctrine\DBAL\Connection' => function (ContainerInterface $container) {
        $pdo = $container->get(PDO::class);
        $config = $container->get(Doctrine\DBAL\Configuration::class);

        return Doctrine\DBAL\DriverManager::getConnection(compact('pdo'), $config);
    },
    'Spot\Locator' => function (ContainerInterface $container) {
        $config = $container->get(Spot\Config::class);

        return new Spot\Locator($config);
    },
    'Spot\Config' => function (ContainerInterface $container) {
        $config = new Spot\Config();
        $settings = $container->get('settings');

        if (empty($settings['db'])) {
            return $config;
        }

        foreach ($settings['db'] as $name => $db) {
            $pdo = new PDO($db['dsn'], $db['user'], $db['password']);

            $config->addConnection($name, compact('pdo'));
        }

        return $config;
    }
];
