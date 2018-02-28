<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Container\ContainerInterface;

return [
    'Elasticsearch\Client' => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        // @FIXME WIP
        //$client = new Elasticsearch\Client($settings['elasticsearch']);
    }
];