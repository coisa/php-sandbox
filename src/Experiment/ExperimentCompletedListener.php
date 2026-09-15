<?php

declare(strict_types=1);

namespace App\Experiment;

final class ExperimentCompletedListener
{
    public function __invoke(ExperimentCompleted $event): void
    {
        $event->trace[] = 'Observed ' . $event->name;
    }
}
