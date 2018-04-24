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
            'level' => getenv('DEBUG') ? Monolog\Logger::DEBUG : Monolog\Logger::INFO,
            'handlers' => [
                Monolog\Handler\StreamHandler::class
            ]
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

        // Auth0
        'auth0' => [
            'domain' => getenv('AUTH0_DOMAIN'),
            'client_id' => getenv('AUTH0_CLIENT_ID'),
            'client_secret' => getenv('AUTH0_CLIENT_SECRET'),
            'redirect_uri' => getenv('AUTH0_CALLBACK_URL'),
            'scope' => 'openid profile',
            'persist_id_token' => true,
            'persist_access_token' => true,
            'persist_refresh_token' => true,
        ],

        // Content Negociation
        'content-negotiation' => [
            'App\Web\JsonResponder' => [
                'application/json',
                'application/*+json',
            ],
            'App\Web\PlatesResponder' => [
                'text/html'
            ]
        ],

        // Social Authorization Providers
        'github' => [
            'clientId'          => getenv('GITHUB_CLIENT_ID'),
            'clientSecret'      => getenv('GITHUB_CLIENT_SECRET'),
            'redirectUri'       => getenv('GITHUB_CLIENT_REDIRECT_URI'),
        ]
    ]
];
