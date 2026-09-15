<?php

declare(strict_types=1);

namespace App\Experiment;

use FastForward\Enum\Trait\HasValues;

enum ExperimentStatus: string
{
    use HasValues;

    case Ready = 'ready';
    case Completed = 'completed';
}
