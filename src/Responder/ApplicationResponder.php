<?php

namespace Application\Web\Responder;

use Psr\Http\Message\ResponseInterface;

/**
 * Class ApplicationResponder
 * @package Application\Web\Responder
 */
abstract class ApplicationResponder
{
    /** @var string */
    private $accept;

    /** @var ResponseInterface */
    private $response;

    /** @var \League\Plates\Engine|null */
    private $renderer;

    protected $available = array();

    /**
     * ApplicationResponder constructor.
     * @param string $accept
     * @param ResponseInterface $response
     * @param null $renderer
     */
    public function __construct(
        string $accept,
        ResponseInterface $response,
        $renderer = null
    ) {
        $this->accept = $accept;
        $this->response = $response;
        $this->renderer = $renderer;
    }

    protected function notFound()
    {
        // @TODO adicionar template de erro de 404?

        return $this->response->withStatus(404, 'Not Found');
    }
}