<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'Psr\SimpleCache\CacheInterface' => function (ContainerInterface $container) {
        return $container->get(chillerlan\SimpleCache\Cache::class);
    },
    'Redis' => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        $redis = new Redis();
        $redis->pconnect(
            $settings['redis']['host'] ?? '127.0.0.1',
            $settings['redis']['port'] ?? 6379
        );

        return $redis;
    },
    'chillerlan\SimpleCache\Drivers\RedisDriver' => function (ContainerInterface $container) {
        $redis = $container->get(Redis::class);

        return new chillerlan\SimpleCache\Drivers\RedisDriver($redis);
    },
    'chillerlan\SimpleCache\Cache' => function (ContainerInterface $container) {
        $driver = $container->get(chillerlan\SimpleCache\Drivers\RedisDriver::class);

        return new chillerlan\SimpleCache\Cache($driver);
    }
];
