<?php

namespace Application\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Http\Body;

/**
 * Class ApplicationController
 *
 * @package Application\Controller
 */
abstract class ApplicationController
{
    /** @var \PDO */
    private $dataSource;

    /** @var \League\Plates\Engine */
    private $renderer;

    /** @var \Aura\Session\Session */
    private $session;

    /** @var \League\Event\Emitter */
    private $eventEmitter;

    /**
     * ApplicationController constructor.
     *
     * @param ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->dataSource   = $container->get(\PDO::class);
        $this->session      = $container->get(\Aura\Session\Session::class);
        $this->renderer     = $container->get(\League\Plates\Engine::class);
        $this->eventEmitter = $container->get(\League\Event\Emitter::class);
    }

    /**
     * @return \PDO
     */
    protected function getDataSource(): \PDO
    {
        return $this->dataSource;
    }

    /**
     * @return \Aura\Session\Session
     */
    protected function getSession(): \Aura\Session\Session
    {
        return $this->session;
    }

    /**
     * @return \League\Plates\Engine
     */
    protected function getRenderer(): \League\Plates\Engine
    {
        return $this->renderer;
    }

    /**
     * @return \League\Event\Emitter
     */
    protected function getEventEmitter(): \League\Event\Emitter
    {
        return $this->eventEmitter;
    }

    /**
     * @param $action
     * @param array $params
     *
     * @return StreamInterface
     */
    protected function render($action, array $params = []): StreamInterface
    {
        $output = $this->getRenderer()->render($action, $params);

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);

        return $body;
    }
}