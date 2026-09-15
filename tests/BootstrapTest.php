<?php

declare(strict_types=1);

namespace App\Tests;

use App\Bootstrap;
use App\Console\Application;
use App\Experiment\RunExperiments;
use FastForward\Clock\FrozenClock;
use FastForward\Clock\SystemClock;
use FastForward\Config\ConfigInterface;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

final class BootstrapTest extends TestCase
{
    public function testReleasedComponentsWorkTogetherWithAnOverriddenClock(): void
    {
        $clock = new FrozenClock('2026-01-01T12:00:00+00:00');
        $container = Bootstrap::create($clock);

        self::assertSame($clock, $container->get(ClockInterface::class));
        self::assertSame('PHP FastForward sandbox', $container->get(ConfigInterface::class)->get('app.name'));
        self::assertSame($container->get(RunExperiments::class), $container->get(RunExperiments::class));

        $expected = [
            'name' => 'PHP FastForward sandbox',
            'time' => '2026-01-01T12:00:00+00:00',
            'status' => 'completed',
            'available_statuses' => ['ready', 'completed'],
            'chunks' => [['alpha', 'beta'], ['gamma', 'delta'], ['epsilon']],
            'event_trace' => ['Observed PHP FastForward sandbox'],
            'cleanup_trace' => ['scope body', 'second registered', 'first registered'],
        ];

        self::assertSame($expected, $container->get(RunExperiments::class)->run());
        self::assertSame($expected, $container->get(RunExperiments::class)->run());
        self::assertSame($expected, json_decode(
            $container->get(Application::class)->run(),
            true,
            flags: JSON_THROW_ON_ERROR,
        ));
    }

    public function testDefaultClockUsesConfiguredUtcTimezone(): void
    {
        $clock = Bootstrap::create()->get(ClockInterface::class);

        self::assertInstanceOf(SystemClock::class, $clock);
        self::assertSame('UTC', $clock->now()->getTimezone()->getName());
    }

    public function testHistoricalSpecimensAreOutsideTheApplicationAutoloader(): void
    {
        self::assertFalse(class_exists('App\\ORM\\Entity\\ImmutableEntity'));
        self::assertFalse(class_exists('App\\WebSocket\\NotificationServer'));
    }
}
