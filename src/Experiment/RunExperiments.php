<?php

declare(strict_types=1);

namespace App\Experiment;

use FastForward\Config\ConfigInterface;
use FastForward\Defer\DeferInterface;
use FastForward\Iterator\ChunkedIteratorAggregate;
use Psr\Clock\ClockInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use function FastForward\Defer\scope;

final readonly class RunExperiments
{
    public function __construct(
        private ConfigInterface $config,
        private ClockInterface $clock,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    /** @return array<string, mixed> */
    public function run(): array
    {
        $name = $this->config->get('app.name');
        $event = new ExperimentCompleted($name);
        $this->dispatcher->dispatch($event);

        $cleanup = [];
        scope(static function (DeferInterface $defer) use (&$cleanup): void {
            $defer(static function () use (&$cleanup): void {
                $cleanup[] = 'first registered';
            });
            $defer(static function () use (&$cleanup): void {
                $cleanup[] = 'second registered';
            });
            $cleanup[] = 'scope body';
        });

        return [
            'name' => $name,
            'time' => $this->clock->now()->format(DATE_ATOM),
            'status' => ExperimentStatus::Completed->value,
            'available_statuses' => ExperimentStatus::values(),
            'chunks' => iterator_to_array(new ChunkedIteratorAggregate(
                $this->config->get('experiment.items'),
                $this->config->get('experiment.chunk_size'),
            )),
            'event_trace' => $event->trace,
            'cleanup_trace' => $cleanup,
        ];
    }
}
