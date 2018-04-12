<?php

namespace Application\ORM\Entity;

/**
 * Interface EntityInterface
 *
 * @package Application\ORM\Entity
 */
interface EntityInterface
{
    /**
     * @return array
     */
    public function toArray(): array;
}