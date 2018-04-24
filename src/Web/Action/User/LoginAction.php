<?php

namespace App\Web\Action\User;

use Auth0\SDK\Auth0;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LoginAction
 *
 * @package App\Web\Action\User
 */
class LoginAction
{
    /** @var Auth0 */
    private $auth0;

    /**
     * LoginAction constructor.
     *
     * @param Auth0 $auth0
     */
    public function __construct(Auth0 $auth0)
    {
        $this->auth0 = $auth0;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->auth0->login();
    }
}
