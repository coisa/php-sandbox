<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'events' => function (ContainerInterface $container) {
        $logger = $container->get('logger');
        $emitter = new League\Event\Emitter();

        // Set your listeners here!
        // $emitter->addListener($event, $listener, $priority);

        $emitter->addListener('user.created', function (Spot\Entity $user) use ($logger) {
            $logger->info(sprintf('The user %s was created!', $user->get('username')));
        });

        $emitter->addListener('user.authenticated', function (Spot\Entity $user) use ($logger) {
            $logger->info(sprintf('The user %s was logged in!', $user->get('username')));
        });

        return $emitter;
    },
];