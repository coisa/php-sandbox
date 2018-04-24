<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Application scope isolation
 */
call_user_func(function () {
    chdir(dirname(__DIR__));

    $app = new App\Web\Application(
        require __DIR__ . '/../config/container.php'
    );

    return $app->run();
});
