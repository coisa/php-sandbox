<?php

declare(strict_types=1);

namespace App\Experiment;

final class ExperimentCompleted
{
    /** @var list<string> */
    public array $trace = [];

    public function __construct(public readonly string $name)
    {
    }
}
