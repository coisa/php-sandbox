<?php

return [
    'settings' => [
        // PHP ini_set
        'php' => [
            'session.save_handler' => 'redis',
            'session.save_path' => getenv('REDIS_DSN'),
        ],

        // Environment config
        'debug'         => getenv('DEBUG') ?: false,

        // Plates Template Renderer configs
        'plates'        => [
            'path'      => getenv('TEMPLATES_PATH') ?: __DIR__ . '/../templates',
            'extension' => getenv('TEMPLATES_EXTENSION') ?: 'php'
        ],

        // Logger configs
        'logger'        => [
            'path'  => __DIR__ . '/../data/logs/',
            'level' => getenv('LOG_LEVEL') ?: Monolog\Logger::INFO
        ],

        // Database connection config
        'db'            => [
            'default' => [
                'dsn'      => getenv('DB_DSN'),
                'user'     => getenv('DB_USER'),
                'password' => getenv('DB_PASS')
            ]
        ],

        // RabbitMQ
        'rabbitmq'      => [
            'connection' => [
                'host'     => getenv('RABBITMQ_HOST') ?: '127.0.0.1',
                'port'     => getenv('RABBITMQ_PORT') ?: 5672,
                'user'     => getenv('RABBITMQ_USER'),
                'password' => getenv('RABBITMQ_PASS'),
                'vhost'    => getenv('RABBITMQ_VHOST')
            ],
            'channels'   => [
                'default' => [
                    'exchange' => 'my-exchange',
                    'queue'    => 'my-channel'
                ]
            ]
        ],

        // Http Cache
        'http-cache' => [
            'type' => 'public',
            'maxAge' => 86400
        ]
    ]
];