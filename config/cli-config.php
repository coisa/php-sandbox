<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\ConsoleRunner;

$dsn = getenv('DSN', false);
if (!$dsn) {
    throw new \RuntimeException('DSN connection settings not found');
}

$connection = DriverManager::getConnection(['url' => $dsn], new Configuration());

return ConsoleRunner::createHelperSet($connection);