<?php

namespace Application\Controller\OAuth2;

use League\OAuth2\Client\Provider\Github;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class GithubAuthorizeAction
 *
 * @package Application\Controller\OAuth2
 */
class GithubAuthorizeAction
{
    /**
     * @var Github OAuth2 Provider
     */
    private $provider;

    /**
     * GithubAuthorizeAction constructor.
     *
     * @param Github $provider
     */
    public function __construct(Github $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $arguments
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, array $arguments = []): ResponseInterface
    {
        parse_str($request->getUri()->getQuery(), $query);

        $code = $query['code'] ?? null;

        if (!$code) {
            $_SESSION['oauth2github']['state'] = $this->provider->getState();

            return $response
                ->withHeader('Location', $this->provider->getAuthorizationUrl())
                ->withStatus(301);
        }

        $state = $query['state'] ?? null;

        if ($state !== $_SESSION['oauth2github']['state']) {
            return $response->withStatus(403, 'Invalid state!');
        }

        $token = $this->provider->getAccessToken('authorization_code', compact('code'));

        try {
            // We got an access token, let's now get the user's details
            $user = $this->provider->getResourceOwner($token);

            // Use these details to create a new profile
            printf('Hello %s!', $user->getNickname());
        } catch (\Exception $e) {

            // Failed to get user details
            exit('Oh dear...');
        }

        $_SESSION['oauth2github'] = $token->getValues();

        return $response
            ->withAddedHeader('Authorization', 'Bearer ' . $token->getToken());
    }
}
