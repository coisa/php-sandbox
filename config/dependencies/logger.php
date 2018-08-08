<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => function (ContainerInterface $container) {
        return $container->get(Logger::class);
    },
    Logger::class => function (ContainerInterface $container) {
        $logger = new Logger('app');
        $logger->pushHandler($container->get(Handler\HandlerInterface::class));

        // Alternative access to logger **not encouraged**
        Monolog\Registry::addLogger($logger);

        return $logger;
    },
    Handler\HandlerInterface::class => function (ContainerInterface $container) {
        // Choose your below strategy

        // Starts handling right away.
        // If you are dealing with a daemon process this probably will be your best choice.
        return $container->get(Handler\GroupHandler::class);

        // Starts handling in the first sign of an error.
        // If your requests mostly come from a browser it's ok to handle the log entries only when an error occurs.
        // In some daemon processes it's ok too. But in a "worker" daemon process is recommended write right away.
        return $container->get(Handler\FingersCrossedHandler::class);

        // A lazy boy. Only starts handling in the end of script execution.
        // It is not wise choose this for a daemon processes
        // Deal with log entries in the end of request seems ok to me too.
        return $container->get(Handler\BufferHandler::class);

        // This is not a totally lazy guy.
        // He starts handling in the end of script execution but at least removes duplicate log entries.
        // If you are having trouble dealing with duplicate log entries choose this guy until you find out how to solve the real problem
        return $container->get(Handler\DeduplicationHandler::class);

        // If for some odd reason you want to stop handling logs that is what you are looking for
        return $container->get(Handler\NullHandler::class);
    },
    Handler\FingersCrossedHandler::class => function (ContainerInterface $container) {
        $groupHandler = $container->get(Handler\GroupHandler::class);

        return new Handler\FingersCrossedHandler($groupHandler, Logger::ERROR);
    },
    Handler\BufferHandler::class => function (ContainerInterface $container) {
        $groupHandler = $container->get(Handler\GroupHandler::class);

        return new Handler\BufferHandler($groupHandler);
    },
    Handler\DeduplicationHandler::class => function (ContainerInterface $container) {
        $groupHandler = $container->get(Handler\GroupHandler::class);

        return new Handler\DeduplicationHandler($groupHandler);
    },
    Handler\GroupHandler::class => function (ContainerInterface $container) {
        // Put everything on the bucket
        $handlers = [
            $container->get(Handler\BrowserConsoleHandler::class),
            $container->get(Handler\ChromePHPHandler::class),
            $container->get(Handler\StreamHandler::class),
            $container->get(Handler\ErrorLogHandler::class),
            $container->get(Handler\RedisHandler::class),
            $container->get(Handler\RavenHandler::class),
            $container->get('Rollbar\Monolog\Handler\RollbarHandler'),
        ];

        // Then make a little cleanup
        foreach ($handlers as $index => $handler) {
            if (!$handler instanceof Handler\NullHandler) {
                continue;
            }
            unset($handlers[$index]);
        }

        // Just to be sure that we don't break the execution with a raised handler exception
        return new Handler\WhatFailureGroupHandler($handlers);
    },
    Handler\BrowserConsoleHandler::class => function (ContainerInterface $container) {
        return new Handler\BrowserConsoleHandler();
    },
    Handler\ChromePHPHandler::class => function (ContainerInterface $container) {
        return new Handler\ChromePHPHandler();
    },
    Handler\StreamHandler::class => function (ContainerInterface $container) {
        return new Handler\StreamHandler('php://stdout', Logger::DEBUG);
    },
    Handler\ErrorLogHandler::class => function (ContainerInterface $container) {
        return new Handler\ErrorLogHandler(null, Logger::ERROR);
    },
    Handler\SyslogHandler::class => function (ContainerInterface $container) {
        // Make sure that your container has a distinguishable hostname (or simply change the only parameter below)
        return new Handler\SyslogHandler(gethostname());
    },
    Handler\NullHandler::class => function (ContainerInterface $container) {
        return new Handler\NullHandler();
    },
    Handler\RedisHandler::class => function (ContainerInterface $container) {
        if (!class_exists('\Redis')) {
            return $container->get(Handler\NullHandler::class);
        }

        if (!$container->has(\Redis::class)) {
            return $container->get(Handler\NullHandler::class);
        }

        return new Handler\RedisHandler($container->get(\Redis::class), 'monolog');
    },
    Handler\RavenHandler::class => function (ContainerInterface $container) {
        if (!$container->has('Raven_Client')) {
            return $container->get(Handler\NullHandler::class);
        }

        $ravenClient = $container->get('Raven_Client');
        if (!$ravenClient) {
            return $container->get(Handler\NullHandler::class);
        }

        return new Handler\RavenHandler($ravenClient);
    },
    // We don't know if this class was installed. So we do not try to autoload before the container's call.
    'Rollbar\Monolog\Handler\RollbarHandler' => function (ContainerInterface $container) {
        if (!class_exists('Rollbar\Monolog\Handler\RollbarHandler', true)) {
            return $container->get(Handler\NullHandler::class);
        }

        $rollbarLogger = $container->get(\Rollbar\RollbarLogger::class);

        if (!$rollbarLogger instanceof \Rollbar\Monolog\Handler\RollbarHandler) {
            return $container->get(Handler\NullHandler::class);
        }

        return new \Rollbar\Monolog\Handler\RollbarHandler($rollbarLogger);
    },
    // Same thing here. This class is not a composer requirement.
    'Rollbar\RollbarLogger' => function (ContainerInterface $container) {
        if (!class_exists('Rollbar\Rollbar', true)) {
            return null;
        }

        $rollabarLogger = \Rollbar\Rollbar::logger();

        // Make sure that at this point you already initialized \Rollbar\Rollbar::init
        if (!$rollabarLogger instanceof \Rollbar\RollbarLogger) {
            return null;
        }

        // We don't need to worry about a disabled logger handler
        if ($rollabarLogger->disabled()) {
            return null;
        }

        return $rollabarLogger;
    },
    'Raven_Client' => function (ContainerInterface $container) {
        if (!class_exists('Raven_Client', true)) {
            return null;
        }

        $dsn = getenv('SENTRY_DSN');
        if (!$dsn) {
            return null;
        }

        return new Raven_Client($dsn);
    }
];
