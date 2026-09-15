<?php

declare(strict_types=1);

namespace App\Console;

use App\Experiment\RunExperiments;

final readonly class Application
{
    public function __construct(private RunExperiments $experiments)
    {
    }

    public function run(): string
    {
        return json_encode(
            $this->experiments->run(),
            JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES,
        ) . PHP_EOL;
    }
}
