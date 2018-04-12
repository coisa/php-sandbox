<?php

namespace Application\ORM\Entity;

use Application\ORM\Schema\UserTable;

/**
 * Class UserEntity
 *
 * @package Application\ORM\Entity
 */
final class UserEntity extends ImmutableEntity
{
    /**
     * UserEntity constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(new UserTable(), $data);
    }
}