<?php

namespace App\Web\Responder;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ResponderInterface
 *
 * @package App\Web\Responder
 */
interface ResponderInterface
{
    /**
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function respond(ResponseInterface $response): ResponseInterface;

    /**
     * @param array $attributes
     *
     * @return ResponseInterface
     */
    public static function withAttributes(array $attributes): ResponseInterface;
}
