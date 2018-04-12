<?php

namespace Application\ORM\Entity;

/**
 * Class ImmutableEntity
 *
 * @package Application\ORM\Entity
 */
class ImmutableEntity implements EntityInterface
{
    /** @var array */
    private $attributes = [];

    /**
     * ImmutableEntity constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value): void
    {}

    /**
     * @param string $name
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
    {}

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return ImmutableEntity
     */
    public function withAttributes(array $attributes): ImmutableEntity
    {
        return new ImmutableEntity(
            array_merge($this->attributes, $attributes)
        );
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return ImmutableEntity
     */
    public function withAttribute(string $name, mixed $value): ImmutableEntity
    {
        $attributes = $this->attributes;
        $attributes[$name] = $value;

        return new ImmutableEntity(
            array_merge($this->attributes, [
                $name => $value
            ])
        );
    }
}