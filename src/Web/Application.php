<?php

namespace App\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

/**
 * Class Application
 *
 * @package App\Web
 */
class Application
{
    /** @var App */
    private $app;

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->app = new App($container);

        $this->bootstrap(
            require 'config/acl.php',
            require 'config/routes.php',
            require 'config/middlewares.php'
        );
    }

    /**
     * @return ResponseInterface
     */
    public function __invoke()
    {
        return $this->run();
    }

    /**
     * Initializes application file stack
     *
     * @param callable[] ...$initializers
     */
    private function bootstrap(callable ...$initializers)
    {
        foreach ($initializers as $initializer) {
            $initializer($this);
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->app->getContainer();
    }

    /**
     * @return App
     */
    public function getRouter(): App
    {
        return $this->app;
    }

    /**
     * @param bool $silent
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run($silent = false)
    {
        return $this->app->run($silent);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->app->process($request, $response);
    }
}
