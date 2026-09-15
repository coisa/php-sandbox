<?php

declare(strict_types=1);

namespace App\Web\Responder;

use Psr\Http\Message\ResponseInterface;

interface ResponderInterface
{
    public function respond(ResponseInterface $response, mixed $params = null): ResponseInterface;
}
