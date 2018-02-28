<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\App;

/**
 * Define application routes
 */
return function (App $app) {
    $app->get('/', 'Application\Controller\IndexController:indexAction');
};