<?php

namespace App\ORM\Repository;

use App\ORM\Entity\EntityInterface;

/**
 * Interface RepositoryInterface
 *
 * @package App\ORM\TableRepository
 */
interface RepositoryInterface
{
    /**
     * @param array $data
     *
     * @return EntityInterface
     */
    public function newEntity(array $data): EntityInterface;

    /**
     * @param array $data
     *
     * @return EntityInterface
     */
    public function newImmutableEntity(array $data): EntityInterface;
}
