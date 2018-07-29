<?php

namespace App\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Http\Response;

/**
 * Class Application
 *
 * @package App\Web
 */
class Application implements RequestHandlerInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var Router */
    private $router;

    /** @var \SplQueue */
    private $middlewares;

    /** @var App */
    private $app;

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->middlewares = new \SplQueue();

        $this->router = new Router();

        $this->app = new App($container);

        $this->pipeline(
            require 'config/acl.php',
            require 'config/routes.php',
            require 'config/middlewares.php'
        );
    }

    /**
     * Initializes application file stack
     *
     * @param callable[] ...$initializers
     */
    private function pipeline(callable ...$initializers)
    {
        foreach ($initializers as $initializer) {
            $initializer($this);
        }
    }

    /**
     * Returns the application container
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Add middleware to stack execution.
     *
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middlewares->enqueue($middleware);
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Exception
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     * @TODO WIP
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->middlewares->isEmpty()) {
            // @TODO define what to do here!
            return $this->app->process($request, new Response());
        }

        $middleware = $this->middlewares->dequeue();

        return $middleware->process($request, $this);
    }

    /**
     * @return App
     * @deprecated
     */
    public function getRouter(): App
    {
        return $this->app;
    }

    /**
     * @param bool $silent
     *
     * @return ResponseInterface
     * @throws \Exception
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function run($silent = false)
    {
        return $this->app->run($silent);
    }
}
