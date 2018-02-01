<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'rabbitmq' => function (ContainerInterface $container) {
        return $container->get('rabbitmq.queues')['default'];
    },
    'rabbitmq.connection' => function (ContainerInterface $container) {
        $settings = $container->get('settings');
        $connection = $settings['rabbitmq']['connection'];

        return new PhpAmqpLib\Connection\AMQPStreamConnection(
            $connection['host'],
            $connection['port'],
            $connection['user'],
            $connection['password']
        );
    },
    'rabbitmq.channels' => function (ContainerInterface $container) {
        $settings = $container->get('settings');
        $channels = $settings['rabbitmq']['channels'] ?? [];

        /** @var PhpAmqpLib\Connection\AMQPStreamConnection $connection */
        $connection = $container->get('rabbitmq.connection');

        foreach ($channels as $name => $properties) {
            $channel = $connection->channel();

            $channel->queue_declare($properties['queue'], false, true, false, false);
            $channel->exchange_declare($properties['exhange'], 'direct', false, true, false);
            $channel->queue_bind($properties['queue'], $properties['exhange']);

            $channels[$name] = $channel;
        }

        return $channels;
    },
    'rabbitmq.queues' => function (ContainerInterface $container) {
        $settings = $container->get('settings');
        $settings = $settings['rabbitmq']['channels'] ?? [];

        $queues = $container->get('rabbitmq.channels');

        foreach ($queues as $name => $channel) {
            $queues[$name] = new SimpleQueue\Adapter\AmqpQueueAdapter(
                $channel,
                $settings[$name]['queue'],
                $settings[$name]['exchange']
            );
        }

        return $queues;
    }
];