<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Container;

$container = require __DIR__ . '/settings.php';

foreach (glob(__DIR__ . '/dependencies/*.php', GLOB_NOSORT) as $filepath) {
    if (!is_file($filepath)) {
        continue;
    }

    $services = require $filepath;

    if (is_array($services)) {
        $container = array_merge_recursive($container, $services);
    }
}

return new Container($container);
