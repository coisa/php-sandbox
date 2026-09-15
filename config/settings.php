<?php

declare(strict_types=1);

use App\Experiment\ExperimentCompletedListener;
use Psr\EventDispatcher\ListenerProviderInterface;

return [
    DateTimeZone::class => 'UTC',
    'app' => [
        'name' => 'PHP FastForward sandbox',
    ],
    'experiment' => [
        'items' => ['alpha', 'beta', 'gamma', 'delta', 'epsilon'],
        'chunk_size' => 2,
    ],
    ListenerProviderInterface::class => [
        ExperimentCompletedListener::class,
    ],
];
