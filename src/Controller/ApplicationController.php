<?php

namespace Application\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Http\Body;

/**
 * Class ApplicationController
 * @package Application\Controller
 */
abstract class ApplicationController
{
    /**
     * @var |Spot\Locator
     */
    private $db;

    /**
     * @var \League\Plates\Engine
     */
    private $renderer;

    /**
     * @var array
     */
    private $session;

    /**
     * ApplicationController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->db       = $container->get('db');
        $this->session  = $container->get('session');
        $this->renderer = $container->get('renderer');
    }

    /**
     * @param string $action
     * @param array $params
     * @return StreamInterface
     */
    protected function render($action, array $params = []): StreamInterface
    {
        $output = $this->renderer->render($action, $params);

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);

        return $body;
    }
}