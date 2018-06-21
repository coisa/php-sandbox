<?php

namespace App\Web;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router
{
    public function map(array $methods, string $pattern, RequestHandlerInterface $handler)
    {

    }

    public function match(ServerRequestInterface $request)
    {
        // new RouteMatch();
    }
}
