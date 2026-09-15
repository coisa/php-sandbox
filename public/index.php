<?php

declare(strict_types=1);

use App\Web\Application;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;

$container = require __DIR__ . '/../config/container.php';
$request = $container->get(ServerRequestCreatorInterface::class)->fromGlobals();
$response = $container->get(Application::class)->handle($request);

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header($name . ': ' . $value, false);
    }
}

if ($request->getMethod() !== 'HEAD') {
    echo $response->getBody();
}
