<?php

namespace Application\ORM\Repository;

use Application\ORM\Entity\EntityInterface;

/**
 * Interface RepositoryInterface
 *
 * @package Application\ORM\TableRepository
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