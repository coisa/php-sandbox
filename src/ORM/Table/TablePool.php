<?php

namespace Application\ORM\Table;

use Doctrine\DBAL\Schema\Table;

/**
 * Class TablePool
 *
 * @package Application\ORM\Table
 */
class TablePool
{
    /** @var \PDO */
    private $connection;

    /**
     * TablePool constructor.
     *
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Table[] ...$tables
     *
     * @return \SplObjectStorage
     */
    public function __invoke(Table ...$tables): \SplObjectStorage
    {
        return $this->getTableGatewayObjectStorage($tables);
    }

    /**
     * @return \SplObjectStorage
     */
    public function getTableGatewayObjectStorage(array $tables): \SplObjectStorage
    {
        $storage = new \SplObjectStorage();

        foreach ($tables as $table) {
            $storage->attach(new TableGateway($this->connection, $table));
        }

        return $storage;
    }
}