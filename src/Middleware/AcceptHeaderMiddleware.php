<?php

namespace App\Web\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AcceptHeaderMiddleware
 *
 * @package App\Web\Middleware
 */
class AcceptHeaderMiddleware implements MiddlewareInterface
{
    /**
     * @var array
     */
    private $accepts;

    /**
     * AcceptHeaderMiddleware constructor.
     *
     * @param array $accepts
     */
    public function __construct(array $accepts)
    {
        foreach ($accepts as $accept) {
            if (is_string($accept)) {
                // validate class
            }
        }

        $this->accepts = $accepts;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
