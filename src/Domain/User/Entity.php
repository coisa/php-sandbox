<?php

namespace Application\Domain\User;

use CoiSA\Doctrine\DBAL\Types\PasswordType;
use Doctrine\DBAL\Types\Type;
use Spot\Entity as SpotEntity;
use Spot\EventEmitter;
use Spot\Mapper;

/**
 * Class Entity
 * @package Application\Domain\User
 */
class Entity extends SpotEntity
{
    /** @var string */
    protected static $table = 'users';

    /** @var string */
    protected static $mapper = 'Application\Domain\User\Mapper';

    /**
     * @inheritdoc
     */
    public static function fields(): array
    {
        if (!Type::hasType(PasswordType::NAME)) {
            Type::addType(PasswordType::NAME, PasswordType::class);
        }

        return [
            'id'          => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'username'    => ['type' => 'string', 'unique' => true, 'required' => true],
            'password'    => ['type' => PasswordType::NAME, 'required' => true],
            'created_at'  => ['type' => 'datetime', 'columnDefinition' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'],
            'modified_at' => ['type' => 'datetime', 'columnDefinition' => 'DATETIME NULL ON UPDATE CURRENT_TIMESTAMP']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function events(EventEmitter $eventEmitter): EventEmitter
    {
        $eventEmitter->on('beforeCreate', function (SpotEntity $entity, Mapper $mapper) {
            $entity->set('password', password_hash($entity->get('password'), PASSWORD_DEFAULT));
            $entity->set('created_at', new \DateTime());
        });

        $eventEmitter->on('beforeUpdate', function (SpotEntity $entity, Mapper $mapper) {
            if ($entity->isModified('password')) {
                $entity->set('password', password_hash($entity->get('password'), PASSWORD_DEFAULT));
            }

            if ($entity->isModified()) {
                $entity->set('modified_at', new \DateTime());
            }
        });

        return $eventEmitter;
    }
}