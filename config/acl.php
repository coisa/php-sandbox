<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Define application access control list
 */
return function (App\Web\Application $app) {
    $acl = new Zend\Permissions\Acl\Acl();

    // Define ACL Roles
    $acl->addRole(new Zend\Permissions\Acl\Role\GenericRole('guest'));
    $acl->addRole(new Zend\Permissions\Acl\Role\GenericRole('admin'));

    // Define ACL Rules
    $acl->allow('admin');

    return $acl;
};
