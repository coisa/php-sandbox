<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Registry;
use Psr\Container\ContainerInterface;

return [
    'logger' => function (ContainerInterface $container) {
        $logger = new Logger('app');

        $logger->pushHandler($container->get('logger.debug_stream_handler'));
        $logger->pushHandler($container->get('logger.app_stream_handler'));
        $logger->pushHandler($container->get('logger.error_log_handler'));

        // Alternative access to logger **not encouraged**
        Registry::addLogger($logger);

        return $logger;
    },
    'logger.debug_stream_handler' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];

        return new StreamHandler($settings['path'] . '/debug.log', Logger::DEBUG);
    },
    'logger.app_stream_handler' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];

        return new StreamHandler($settings['path'] . '/app.log', $settings['loglevel']);
    },
    'logger.error_log_handler' => function (ContainerInterface $container) {
        return new ErrorLogHandler();
    },
];
