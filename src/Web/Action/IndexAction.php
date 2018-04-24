<?php

namespace App\Web\Action;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Http\Body;

/**
 * Class IndexAction
 *
 * @package App\Web\Action
 */
class IndexAction
{
    /** @var Engine */
    private $engine;

    /**
     * IndexAction constructor.
     *
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withBody($this->render('index/index'));
    }

    /**
     * @param $action
     * @param array $params
     *
     * @return StreamInterface
     */
    protected function render($action, array $params = []): StreamInterface
    {
        $output = $this->engine->render($action, $params);

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);

        return $body;
    }
}
