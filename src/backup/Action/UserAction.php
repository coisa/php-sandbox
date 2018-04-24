<?php

namespace App\Action;

use App\Domain\User;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class UserAction
 * @package App\Action
 */
class UserAction
{
    /**
     * @var User\Service
     */
    private $service;

    /**
     * @var \League\Event\Emitter
     */
    private $events;

    /**
     * UserAction constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $connection = $container->get('db');
        $mapper = $connection->mapper('App\\Domain\\User\\Entity');

        $this->service = new User\Service($mapper);
        $this->events = $container->get('events');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function add(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // @TODO retornar a view
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // @TODO retornar a view
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $created = $this->service->create($data);

        if ($created instanceof User\Entity) {
            $this->events->emit('user.created', $created);

            return $response->withStatus(201);
        }

        return $response->withStatus(500);
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function auth(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $authenticated = $this->service->authenticate($data['username'], $data['password']);

        if ($authenticated instanceof User\Entity) {
            $this->events->emit('user.authenticated', $authenticated);

            return $response;
        }

        return $response->withStatus(404);
    }
}
