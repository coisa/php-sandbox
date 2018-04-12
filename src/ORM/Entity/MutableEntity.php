<?php

namespace Application\ORM\Entity;

/**
 * Class MutableEntity
 *
 * @package Application\ORM\Entity
 */
class MutableEntity implements EntityInterface
{
    /** @var array */
    protected $attributes = [];

    /**
     * MutableEntity constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value): void
    {
        $method = 'set' . ucwords($name);

        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } else {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @param string $name
     */
    public function __unset(string $name): void
    {
        $this->attributes[$name] = null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}