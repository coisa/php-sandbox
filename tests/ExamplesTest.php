<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ExamplesTest extends TestCase
{
    #[DataProvider('examples')]
    public function testExamplesRunFromAnotherWorkingDirectory(string $entrypoint, array $expected): void
    {
        $process = proc_open([PHP_BINARY, dirname(__DIR__) . '/' . $entrypoint], [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes, __DIR__);
        self::assertIsResource($process);
        fclose($pipes[0]);
        $output = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        self::assertSame(0, proc_close($process), $errors);
        self::assertSame('', $errors);
        $data = json_decode($output, true, flags: JSON_THROW_ON_ERROR);
        foreach ($expected as $key => $value) {
            self::assertSame($value, $data[$key]);
        }
    }

    public static function examples(): iterable
    {
        yield 'cli' => ['bin/app', ['status' => 'completed']];
        yield 'in-memory server' => ['examples/http.php', ['status' => 200]];
        yield 'mocked client' => [
            'examples/http-client.php',
            ['status' => 200, 'body' => ['source' => 'in-memory fixture'], 'transport_requests' => 1],
        ];
    }
}
