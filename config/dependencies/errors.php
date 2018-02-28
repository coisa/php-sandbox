<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'Whoops\Run' => function (ContainerInterface $container) {
        $run = new Whoops\Run();

        if (getenv('DEBUG', false)) {
            $run->pushHandler($container->get(Whoops\Handler\PrettyPageHandler::class));
        }

        if (Whoops\Util\Misc::isAjaxRequest()) {
            $run->pushHandler($container->get(Whoops\Handler\JsonResponseHandler::class));
        }

        $run->pushHandler($container->get(Whoops\Handler\PlainTextHandler::class));

        return $run;
    },
    'Whoops\Handler\PrettyPageHandler' => function (ContainerInterface $container) {
        return new Whoops\Handler\PrettyPageHandler;
    },
    'Whoops\Handler\JsonResponseHandler' => function (ContainerInterface $container) {
        return new Whoops\Handler\JsonResponseHandler;
    },
    'Whoops\Handler\PlainTextHandler' => function (ContainerInterface $container) {
        $logger = $container->get(Psr\Log\LoggerInterface::class);

        $handler = new Whoops\Handler\PlainTextHandler($logger);
        $handler->loggerOnly(true);

        return $handler;
    },
    'Zeuxisoo\Whoops\Provider\Slim\WhoopsErrorHandler' => function (ContainerInterface $container) {
        $whoops = $container->get(Whoops\Run::class);

        return new Zeuxisoo\Whoops\Provider\Slim\WhoopsErrorHandler($whoops);
    }
];