<?php

namespace App\ORM\Table;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;

/**
 * Class TableGateway
 *
 * @package App\ORM\Table
 */
class TableGateway
{
    /** @var Connection */
    private $connection;

    /** @var Table */
    private $table;

    /**
     * TableGateway constructor.
     *
     * @param Connection $connection
     * @param Table $table
     */
    public function __construct(Connection $connection, Table $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * @param $primaryKey
     *
     * @return array|null
     */
    public function find($primaryKey): ?array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('*');
        $queryBuilder->from($this->table->getName());
        $queryBuilder->where(
            $this->table->getPrimaryKey() . ' = ?',
            $primaryKey
        );
        $queryBuilder->setMaxResults(1);

        $stmt = $queryBuilder->execute();

        return null;
    }

    // communicate with the database, just it!
}
