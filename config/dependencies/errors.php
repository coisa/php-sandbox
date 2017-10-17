<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'errorHandler' => function (ContainerInterface $container) {
        return $container->get('whoops.error_handler');
    },
    'whoops' => function (ContainerInterface $container) {
        $run = $container->get('whoops.run');

        if (getenv('DEBUG', false)) {
            $run->pushHandler($container->get('whoops.pretty_page_handler'));
        }

        if (Whoops\Util\Misc::isAjaxRequest()) {
            $run->pushHandler($container->get('whoops.json_response_handler'));
        }

        $run->pushHandler($container->get('whoops.plain_text_handler'));

        return $run;
    },
    'whoops.run' => function (ContainerInterface $container) {
        return new Whoops\Run();
    },
    'whoops.pretty_page_handler' => function (ContainerInterface $container) {
        return new Whoops\Handler\PrettyPageHandler;
    },
    'whoops.json_response_handler' => function (ContainerInterface $container) {
        return new Whoops\Handler\JsonResponseHandler;
    },
    'whoops.plain_text_handler' => function (ContainerInterface $container) {
        $logger = $container->get('logger');

        $handler = new Whoops\Handler\PlainTextHandler($logger);
        $handler->loggerOnly(true);

        return $handler;
    },
    'whoops.error_handler' => function (ContainerInterface $container) {
        $whoops = $container->get('whoops');

        return new Zeuxisoo\Whoops\Provider\Slim\WhoopsErrorHandler($whoops);
    }
];