<?php

require_once __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/container.php';

/** @var Slim\App $application */
$application = $container->get('application');

return $application->run();