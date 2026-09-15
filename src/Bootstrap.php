<?php

declare(strict_types=1);

namespace App;

use App\Console\Application as ConsoleApplication;
use App\Experiment\ExperimentCompletedListener;
use App\Experiment\RunExperiments;
use App\Web\Action\IndexAction;
use App\Web\Application;
use App\Web\Responder\JsonResponder;
use App\Web\Responder\ResponderInterface;
use App\Web\Router;
use FastForward\Config\ArrayConfig;
use FastForward\Config\ConfigInterface;
use FastForward\Container\ServiceProvider\ArrayServiceProvider;
use FastForward\Framework\ServiceProvider\FrameworkServiceProvider;
use FastForward\Http\Message\Factory\ResponseFactoryInterface;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function FastForward\Container\container;

final class Bootstrap
{
    public static function create(?ClockInterface $clock = null): ContainerInterface
    {
        $config = new ArrayConfig(require __DIR__ . '/../config/settings.php');

        // Earlier providers win. Tests can replace time before framework services resolve.
        $overrides = new ArrayServiceProvider($clock === null ? [] : [
            ClockInterface::class => static fn(): ClockInterface => $clock,
        ]);

        $services = new ArrayServiceProvider([
            ExperimentCompletedListener::class => static fn() => new ExperimentCompletedListener(),
            RunExperiments::class => static fn(ContainerInterface $container) => new RunExperiments(
                $container->get(ConfigInterface::class),
                $container->get(ClockInterface::class),
                $container->get(EventDispatcherInterface::class),
            ),
            ResponderInterface::class => static fn() => new JsonResponder(),
            IndexAction::class => static fn(ContainerInterface $container) => new IndexAction(
                $container->get(RunExperiments::class),
                $container->get(ResponseFactoryInterface::class),
                $container->get(ResponderInterface::class),
            ),
            Router::class => static function (ContainerInterface $container): Router {
                $router = new Router(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(StreamFactoryInterface::class),
                );
                (require __DIR__ . '/../config/routes.php')($router, $container);

                return $router;
            },
            Application::class => static fn(ContainerInterface $container) => new Application(
                $container->get(Router::class),
            ),
            ConsoleApplication::class => static fn(ContainerInterface $container) => new ConsoleApplication(
                $container->get(RunExperiments::class),
            ),
        ]);

        return container($config, $overrides, $services, FrameworkServiceProvider::class);
    }
}
