<?php

declare(strict_types=1);

use App\Web\Action\IndexAction;
use App\Web\Router;
use Psr\Container\ContainerInterface;

return static function (Router $router, ContainerInterface $container): void {
    $router->map(['GET'], '/', $container->get(IndexAction::class));
    $router->map(['GET'], '/experiments', $container->get(IndexAction::class));
};
