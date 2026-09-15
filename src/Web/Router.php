<?php

declare(strict_types=1);

namespace App\Web;

use FastForward\Http\Message\Factory\ResponseFactoryInterface;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Router implements RequestHandlerInterface
{
    /** @var array<string, array<string, RequestHandlerInterface>> */
    private array $routes = [];

    public function __construct(
        private readonly ResponseFactoryInterface $responses,
        private readonly StreamFactoryInterface $streams,
    ) {
    }

    /** @param list<string> $methods */
    public function map(array $methods, string $pattern, RequestHandlerInterface $handler): void
    {
        if ($methods === [] || !str_starts_with($pattern, '/') || str_contains($pattern, '?')) {
            throw new InvalidArgumentException('A route needs methods and an absolute path without a query.');
        }

        foreach ($methods as $method) {
            if (!preg_match('/^[A-Z]+$/D', $method) || isset($this->routes[$pattern][$method])) {
                throw new InvalidArgumentException('Route methods must be uppercase and unique for each path.');
            }
        }

        foreach ($methods as $method) {
            $this->routes[$pattern][$method] = $handler;
        }
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $path = $request->getUri()->getPath() ?: '/';
        $methods = $this->routes[$path] ?? [];
        $method = $request->getMethod();
        $handler = $methods[$method] ?? ($method === 'HEAD' ? ($methods['GET'] ?? null) : null);

        if ($handler !== null) {
            $response = $handler->handle($request);
        } elseif ($methods !== []) {
            $allowed = array_keys($methods);
            if (isset($methods['GET']) && !isset($methods['HEAD'])) {
                $allowed[] = 'HEAD';
            }
            $response = $this->responses->createResponseFromPayload(['error' => 'Method not allowed'])
                ->withStatus(405)
                ->withHeader('Allow', implode(', ', $allowed));
        } else {
            $response = $this->responses->createResponseFromPayload(['error' => 'Not found'])->withStatus(404);
        }

        return $method === 'HEAD'
            ? $response->withBody($this->streams->createStream())
            : $response;
    }
}
