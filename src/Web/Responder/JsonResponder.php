<?php

namespace App\Web\Responder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class JsonResponder
 *
 * @package App\Web\Responder
 */
class JsonResponder implements ResponderInterface
{
    /** @var string */
    private $charset;

    /**
     * JsonResponder constructor.
     *
     * @param string $charset
     * @param callable $formatter [optional]
     */
    public function __construct(string $charset = 'utf-8')
    {
        $this->charset = $charset;
    }

    /**
     * @see JsonResponder::respond()
     */
    public function __invoke(ResponseInterface $response, mixed $params = null)
    {
        return $this->respond($response, $params);
    }

    /**
     * @param ResponseInterface $response
     * @param mixed $params [optional]
     *
     * @return ResponseInterface
     */
    public function respond(ResponseInterface $response, mixed $params = null): ResponseInterface
    {
        return $response
            ->withHeader(
                'Content-Type',
                'application/json;charset=' . $this->charset
            )
            ->withBody(
                $this->getBody($params)
            );
    }

    /**
     * @param mixed $params [optional]
     *
     * @return string
     */
    private function getContent(mixed $params = null): string
    {
        if (empty($params) || !is_iterable($params) || !is_scalar($params)) {
            return '{}';
        }

        if (is_iterable($params)) {
            $content = json_encode($params);

            if (false !== $content) {
                return $content;
            }

            return json_encode([
                'error' => [
                    'code'    => json_last_error(),
                    'message' => json_last_error_msg()
                ]
            ]);
        }

        return (string) $params;
    }

    /**
     * @param mixed $params
     *
     * @return StreamInterface
     */
    private function getBody(mixed $params): StreamInterface
    {
        $body = new \Slim\Http\Body(\fopen('php://temp', 'r+'));
        $body->write($this->getContent($params));

        return $body;
    }
}
