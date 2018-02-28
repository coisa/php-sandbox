<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'Psr\Log\LoggerInterface' => function (ContainerInterface $container) {
        return $container->get(Monolog\Logger::class);
    },
    'Monolog\Logger' => function (ContainerInterface $container) {
        $logger = new Monolog\Logger('app');

        $logger->pushHandler($container->get('logger.debug_stream_handler'));
        $logger->pushHandler($container->get('logger.app_stream_handler'));

        // Alternative access to logger **not encouraged**
        Monolog\Registry::addLogger($logger);

        return $logger;
    },
    'Monolog\Handler\StreamHandler' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];

        return new Monolog\Handler\StreamHandler($settings['path'] . '/app.log', $settings['loglevel']);
    },
];
