<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'application' => function (ContainerInterface $container) {
        $app = new Slim\App($container);

//        $app->get('/', 'Application\Action\UserAction:index');
        $app->get('/', 'Application\Controller\IndexController:indexAction');

        return $app;
    }
];