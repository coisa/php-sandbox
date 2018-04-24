<?php

namespace App\ORM\Entity;

/**
 * Interface EntityInterface
 *
 * @package App\ORM\Entity
 */
interface EntityInterface
{
    /**
     * @return array
     */
    public function toArray(): array;
}
