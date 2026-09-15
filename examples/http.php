<?php

declare(strict_types=1);

use App\Bootstrap;
use App\Web\Application;
use FastForward\Clock\FrozenClock;
use Psr\Http\Message\ServerRequestFactoryInterface;

require __DIR__ . '/../vendor/autoload.php';

$container = Bootstrap::create(new FrozenClock('2026-01-01T12:00:00+00:00'));
$request = $container->get(ServerRequestFactoryInterface::class)
    ->createServerRequest('GET', 'https://sandbox.example.test/experiments');
$response = $container->get(Application::class)->handle($request);

echo json_encode([
    'status' => $response->getStatusCode(),
    'content_type' => $response->getHeaderLine('Content-Type'),
    'body' => json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR),
], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES) . PHP_EOL;
