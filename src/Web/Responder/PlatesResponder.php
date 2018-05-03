<?php

namespace App\Web\Responder;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Http\Body;

/**
 * Class PlatesResponder
 *
 * @package App\Web\Responder
 */
class PlatesResponder implements ResponderInterface
{
    /** @var ResponseInterface */
    private $response;

    /** @var Engine */
    private $engine;

    /** @var string */
    private $template;

    /** @var array */
    private $params = [];

    /**
     * PlatesResponder constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = clone $response;
    }

    /**
     * @return ResponseInterface
     */
    public function respond(): ResponseInterface
    {
        return clone $this->response
            ->withHeader('Content-Type', 'text/html')
            ->withBody($this->render($this->template, $this->params));
    }

    /**
     * @param Engine $engine
     * @param string $template
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function __invoke(Engine $engine, string $template, array $params = []): ResponseInterface
    {
        return $this
            ->setEngine($engine)
            ->setTemplate($template)
            ->setParams($params)
            ->respond();
    }

    /**
     * @param Engine $engine
     *
     * @return PlatesResponder
     */
    public function setEngine(Engine $engine): PlatesResponder
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @param string $template
     *
     * @return PlatesResponder
     */
    public function setTemplate(string $template): PlatesResponder
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param array $params
     *
     * @return PlatesResponder
     */
    public function setParams(array $params): PlatesResponder
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param string $template
     * @param array $params
     *
     * @return StreamInterface
     */
    protected function render(string $template, array $params = []): StreamInterface
    {
        $output = $this->engine->render($template, $params);

        $body = new Body(\fopen('php://temp', 'r+'));
        $body->write($output);

        return $body;
    }
}
