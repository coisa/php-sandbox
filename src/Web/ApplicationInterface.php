<?php

namespace App\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

interface ApplicationInterface
{
    public function __construct(ContainerInterface $container);

    public function getContainer(): ContainerInterface;

    public function addMiddleware(callable $middleware): ApplicationInterface;

    public function addRoute(string $path, callable $resolver, $name = null): mixed;

    public function __toString(): string;

    public static function respond(ResponseInterface $response): ResponseInterface;
}
