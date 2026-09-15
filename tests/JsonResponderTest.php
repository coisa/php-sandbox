<?php

declare(strict_types=1);

namespace App\Tests;

use App\Bootstrap;
use App\Web\Responder\JsonResponder;
use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;

final class JsonResponderTest extends TestCase
{
    #[DataProvider('payloads')]
    public function testJsonValuesAndResponseMetadataArePreserved(mixed $payload): void
    {
        $original = Bootstrap::create()->get(ResponseFactoryInterface::class)
            ->createResponse(202)->withHeader('X-Example', 'preserved');
        $response = (new JsonResponder())->respond($original, $payload);

        self::assertSame($payload, json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR));
        self::assertSame(202, $response->getStatusCode());
        self::assertSame('preserved', $response->getHeaderLine('X-Example'));
        self::assertSame('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertSame('', (string) $original->getBody());
        self::assertFalse($original->hasHeader('Content-Type'));
    }

    public static function payloads(): iterable
    {
        yield 'false' => [false];
        yield 'zero' => [0];
        yield 'null' => [null];
        yield 'empty array' => [[]];
        yield 'empty string' => [''];
        yield 'unicode object' => [['message' => 'Olá PHP', 'items' => [1, 2]]];
    }

    public function testInvalidUtf8IsNotSilentlyReplacedWithAnEmptyObject(): void
    {
        $response = Bootstrap::create()->get(ResponseFactoryInterface::class)->createResponse();

        $this->expectException(JsonException::class);
        (new JsonResponder())->respond($response, "\xB1");
    }
}
