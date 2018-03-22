<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\App;
use Slim\Http;
use Zend\Permissions\Acl;

/**
 * Define application access control list
 */
return function (App $app) {
    $acl = new Acl\Acl();

    // Define ACL Roles
    $acl->addRole(new Acl\Role\GenericRole('guest'));
    $acl->addRole(new Acl\Role\GenericRole('admin'));

    // Define ACL Rules
    $acl->allow('admin');

    // Add ACL to request attribute
    $app->add(function (Http\Request $request, Http\Response $response, callable $next) use ($acl) {
        return $next($request->withAttribute('acl', $acl), $response);
    });

    return $acl;
};