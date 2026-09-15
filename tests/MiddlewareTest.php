<?php

declare(strict_types=1);

namespace App\Tests;

use App\Bootstrap;
use App\Web\Application;
use ArrayObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareTest extends TestCase
{
    public function testMiddlewareOrderAndPipelineArePreservedAcrossRequests(): void
    {
        $container = Bootstrap::create();
        $trace = new ArrayObject();
        $response = $container->get(ResponseFactoryInterface::class)->createResponse();
        $request = $container->get(ServerRequestFactoryInterface::class)->createServerRequest('GET', '/');

        $handler = new class ($trace, $response) implements RequestHandlerInterface {
            public function __construct(private ArrayObject $trace, private ResponseInterface $response)
            {
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $this->trace[] = 'handler';
                return $this->response;
            }
        };

        $middleware = static fn(string $name) => new class ($name, $trace) implements MiddlewareInterface {
            public function __construct(private string $name, private ArrayObject $trace)
            {
            }

            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                $this->trace[] = $this->name . ':before';
                $response = $handler->handle($request);
                $this->trace[] = $this->name . ':after';

                return $response->withAddedHeader('X-Middleware', $this->name);
            }
        };

        $app = new Application($handler, $middleware('outer'), $middleware('inner'));
        foreach ([1, 2] as $_) {
            self::assertSame(['inner', 'outer'], $app->handle($request)->getHeader('X-Middleware'));
        }

        $oneRequest = ['outer:before', 'inner:before', 'handler', 'inner:after', 'outer:after'];
        self::assertSame([...$oneRequest, ...$oneRequest], $trace->getArrayCopy());
    }
}
