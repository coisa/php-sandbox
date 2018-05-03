<?php

namespace App\Web\Responder;

use Psr\Http\Message\ResponseInterface;

/**
 * Class RedirectResponder
 *
 * @package App\Web\Responder
 */
class RedirectResponder
{
    /** @var string */
    private $location;

    /** @var int */
    private $code;

    /**
     * RedirectResponder constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return ResponseInterface
     */
    public function respond(): ResponseInterface
    {
        return clone $this->response
            ->withHeader('Location', $this->location)
            ->withStatus($this->code);
    }

    /**
     * @param string $location
     * @param int $code
     *
     * @return ResponseInterface
     */
    public function __invoke(string $location, int $code = 301): ResponseInterface
    {
        return $this
            ->setLocation($location)
            ->setCode($code)
            ->respond();
    }

    /**
     * @param string $location
     *
     * @return RedirectResponder
     */
    public function setLocation(string $location): RedirectResponder
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @param int $code
     *
     * @return RedirectResponder
     */
    public function setCode(int $code): RedirectResponder
    {
        $this->code = $code;

        return $this;
    }
}
