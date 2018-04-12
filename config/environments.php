<?php

require_once __DIR__ . '/../vendor/autoload.php';

$debug = (bool) getenv('DEBUG') ?: false;

if (!$debug) {
    return;
}

// Reload .env file for every request on DEBUG mode
// So you will not needed to recreate the container to see the .env changes

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(
    dirname(__DIR__) . '/.env',
    dirname(__DIR__) . '/.env.example'
);
