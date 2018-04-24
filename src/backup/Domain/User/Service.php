<?php

namespace App\Domain\User;

/**
 * Class Service
 * @package App\Domain\User
 */
class Service
{
    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * Service constructor.
     * @param Mapper $mapper
     */
    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param array $data
     * @return Entity|false
     */
    public function create(array $data = [])
    {
        $filtered = Filter::filter($data);

        if (Validator::validate($filtered)) {
            return $this->mapper->insert($data);
        }

        return false;
    }

    public function authenticate($username, $password)
    {
        $user = $this->mapper->getUsingCredentials($username, $password);

        if (!$user) {
            return false;
        }

        $_SESSION['user'] = $user;
    }
}
