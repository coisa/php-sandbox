<?php

require_once __DIR__ . '/../vendor/autoload.php';

/** @var Psr\Container\ContainerInterface $container */
$container = require __DIR__ . '/container.php';

/** @var Doctrine\DBAL\Connection $connection */
$connection = $container->get(Doctrine\DBAL\Connection::class);

return Doctrine\DBAL\Tools\Console\ConsoleRunner::createHelperSet($connection);