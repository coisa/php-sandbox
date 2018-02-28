<?php

namespace Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class IndexController
 *
 * @package Application\Controller
 */
class IndexController extends ApplicationController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function indexAction(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withBody($this->render('index/index'));
    }
}