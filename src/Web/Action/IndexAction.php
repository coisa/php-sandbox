<?php

namespace App\Web\Action;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Body;
use Slim\Http\Response;

/**
 * Class IndexAction
 *
 * @package App\Web\Action
 */
class IndexAction implements RequestHandlerInterface
{
    /** @var Engine */
    private $engine;

    /**
     * IndexAction constructor.
     *
     * @param Engine $engine
     * @param LoggerInterface $logger optional
     */
    public function __construct(Engine $engine, LoggerInterface $logger = null)
    {
        $this->engine = $engine;
        $this->logger = $logger;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this($request, new Response());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        for ($i=0; $i<10;$i++) {
            $this->logger->debug($i.'debug');
            $this->logger->info($i.'info');
            $this->logger->notice($i.'notice');
            $this->logger->warning($i.'warning');
            $this->logger->error($i.'error');
            $this->logger->alert($i.'alert');
            $this->logger->critical($i.'critical');
            $this->logger->emergency($i.'emergency');
        }

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
