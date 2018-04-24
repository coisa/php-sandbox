<?php

namespace App\ORM\Schema;

use CoiSA\Doctrine\DBAL\Types\PasswordType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

/**
 * Class UserTable
 *
 * @package App\ORM\Schema
 */
class UserTable extends Table
{
    /**
     * UserTable constructor.
     *
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        if (!Type::hasType(PasswordType::NAME)) {
            Type::addType(PasswordType::NAME, PasswordType::class);
        }

        parent::__construct('users');

        $this->addColumn('id', 'integer', ['primary' => true, 'autoincrement' => true]);
        $this->addColumn('username', 'string', ['required' => true, 'unique' => true]);
        $this->addColumn('password', PasswordType::NAME, ['required' => true]);
        $this->addColumn('created_at', 'datetime', ['columnDefinition' => 'DATETIME DEFAULT CURRENT_TIMESTAMP']);
        $this->addColumn('modified_at', 'datetime', ['columnDefinition' => 'DATETIME NULL ON UPDATE CURRENT_TIMESTAMP']);
    }

//    public function toSql(): string
//    {
//        $schema = new Schema([$this]);
//
//        return $schema->getMigrateFromSql(new Schema(), $platform);
//    }
//
//    public function __toString(): string
//    {
//        return $this->toSql();
//    }
}
