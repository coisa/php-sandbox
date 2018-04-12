<?php

require_once __DIR__ . '/environments.php';

return [
    'settings' => [
        // PHP ini_set
        'php' => [
            'session.save_handler' => 'redis',
            'session.save_path' => getenv('REDIS_DSN'),
        ],

        // Environment config
        'debug'               => getenv('DEBUG'),
        'displayErrorDetails' => getenv('DEBUG'),

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
                'dsn'      => 'mysql:host=' . getenv('MYSQL_HOST') . ';dbname=' . getenv('MYSQL_DATABASE'),
                'user'     => getenv('MYSQL_USER'),
                'password' => getenv('MYSQL_PASSWORD')
            ]
        ],

        // RabbitMQ
        'rabbitmq'      => [
            'connection' => [
                'host'     => getenv('RABBITMQ_DEFAULT_HOST') ?: '127.0.0.1',
                'port'     => getenv('RABBITMQ_DEFAULT_PORT') ?: 5672,
                'user'     => getenv('RABBITMQ_DEFAULT_USER'),
                'password' => getenv('RABBITMQ_DEFAULT_PASS'),
                'vhost'    => getenv('RABBITMQ_DEFAULT_VHOST')
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
        ],

        // Social Authorization Providers
        'github' => [
            'clientId'          => getenv('GITHUB_CLIENT_ID'),
            'clientSecret'      => getenv('GITHUB_CLIENT_SECRET'),
            'redirectUri'       => getenv('GITHUB_CLIENT_REDIRECT_URI'),
        ]
    ]
];
