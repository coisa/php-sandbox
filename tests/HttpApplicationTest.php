<?php

declare(strict_types=1);

namespace App\Tests;

use App\Bootstrap;
use App\Web\Application;
use App\Web\Router;
use FastForward\Clock\FrozenClock;
use FastForward\Http\Message\Factory\ResponseFactoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestFactoryInterface;

final class HttpApplicationTest extends TestCase
{
    #[DataProvider('routes')]
    public function testHttpRouting(string $method, string $path, int $status, ?string $error): void
    {
        $container = Bootstrap::create(new FrozenClock('2026-01-01T12:00:00+00:00'));
        $request = $container->get(ServerRequestFactoryInterface::class)
            ->createServerRequest($method, 'https://sandbox.example.test' . $path);
        $app = $container->get(Application::class);

        // The same application instance must safely serve more than one request.
        foreach ([1, 2] as $_) {
            $response = $app->handle($request);
            self::assertSame($status, $response->getStatusCode());
            self::assertStringContainsString('application/json', $response->getHeaderLine('Content-Type'));

            if ($method === 'HEAD') {
                self::assertSame('', (string) $response->getBody());
            } else {
                $body = json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);
                self::assertSame($error ?? 'completed', $body['error'] ?? $body['status']);
            }

            if ($status === 405) {
                self::assertSame('GET, HEAD', $response->getHeaderLine('Allow'));
            }
        }
    }

    public static function routes(): iterable
    {
        yield 'index' => ['GET', '/', 200, null];
        yield 'experiment' => ['GET', '/experiments', 200, null];
        yield 'query string' => ['GET', '/experiments?limit=2', 200, null];
        yield 'head' => ['HEAD', '/experiments', 200, null];
        yield 'unknown path' => ['GET', '/missing', 404, 'Not found'];
        yield 'missing head' => ['HEAD', '/missing', 404, 'Not found'];
        yield 'wrong method' => ['POST', '/experiments', 405, 'Method not allowed'];
    }

    public function testDuplicateRoutesAreRejected(): void
    {
        $container = Bootstrap::create();
        $router = $container->get(Router::class);

        $this->expectException(InvalidArgumentException::class);
        $router->map(['GET'], '/', $container->get(Application::class));
    }

    public function testExplicitHeadRouteTakesPrecedenceAndHasNoBody(): void
    {
        $container = Bootstrap::create();
        $router = $container->get(Router::class);
        $response = $container->get(ResponseFactoryInterface::class)->createResponseFromText('ignored');

        $handler = new class ($response) implements \Psr\Http\Server\RequestHandlerInterface {
            public function __construct(private \Psr\Http\Message\ResponseInterface $response)
            {
            }

            public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
            {
                return $this->response->withStatus(202);
            }
        };

        $router->map(['HEAD'], '/', $handler);
        $request = $container->get(ServerRequestFactoryInterface::class)->createServerRequest('HEAD', '/');
        $response = $router->handle($request);

        self::assertSame(202, $response->getStatusCode());
        self::assertSame('', (string) $response->getBody());
    }
}
