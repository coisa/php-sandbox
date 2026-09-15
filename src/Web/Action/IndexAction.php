<?php

declare(strict_types=1);

namespace App\Web\Action;

use App\Experiment\RunExperiments;
use App\Web\Responder\ResponderInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class IndexAction implements RequestHandlerInterface
{
    public function __construct(
        private RunExperiments $experiments,
        private ResponseFactoryInterface $responses,
        private ResponderInterface $responder,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responder->respond($this->responses->createResponse(), $this->experiments->run());
    }
}
