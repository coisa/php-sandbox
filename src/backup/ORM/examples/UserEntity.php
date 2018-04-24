<?php

namespace App\ORM\Entity;

use App\ORM\Schema\UserTable;

/**
 * Class UserEntity
 *
 * @package App\ORM\Entity
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
