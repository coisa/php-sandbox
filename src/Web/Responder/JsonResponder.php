<?php

declare(strict_types=1);

namespace App\Web\Responder;

use FastForward\Http\Message\JsonStream;
use Psr\Http\Message\ResponseInterface;

final readonly class JsonResponder implements ResponderInterface
{
    public function respond(ResponseInterface $response, mixed $params = null): ResponseInterface
    {
        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withBody(new JsonStream($params));
    }
}
