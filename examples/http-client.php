<?php

declare(strict_types=1);

use FastForward\Container\ServiceProvider\ArrayServiceProvider;
use FastForward\Framework\ServiceProvider\FrameworkServiceProvider;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function FastForward\Container\container;

require __DIR__ . '/../vendor/autoload.php';

// http-client v1.1.0 resolves its transport under HttpClient::class.
// Override that entry before the framework provider. No network request is made.
$transport = new MockHttpClient(static function (string $method, string $url): MockResponse {
    if ($method !== 'GET' || $url !== 'https://sandbox.example.test/fixture') {
        throw new LogicException('Unexpected request in the offline example.');
    }

    return new MockResponse('{"source":"in-memory fixture"}', [
        'http_code' => 200,
        'response_headers' => ['content-type: application/json'],
    ]);
});

$container = container(
    new ArrayServiceProvider([HttpClient::class => static fn() => $transport]),
    FrameworkServiceProvider::class,
);
$request = $container->get(RequestFactoryInterface::class)
    ->createRequest('GET', 'https://sandbox.example.test/fixture');
$response = $container->get(ClientInterface::class)->sendRequest($request);

echo json_encode([
    'status' => $response->getStatusCode(),
    'body' => json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR),
    'transport_requests' => $transport->getRequestsCount(),
], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . PHP_EOL;
