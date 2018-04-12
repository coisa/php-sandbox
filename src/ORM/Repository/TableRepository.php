<?php

namespace Application\ORM\Repository;

use Application\ORM\Entity\EntityInterface;
use Application\ORM\Entity\ImmutableEntity;
use Application\ORM\Entity\MutableEntity;
use Application\ORM\Table\TableGateway;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\Table;

/**
 * Class TableRepository
 *
 * @package Application\ORM\TableRepository
 */
class TableRepository implements RepositoryInterface
{
    /** @var \PDO */
    private $connection;

    /** @var Table */
    private $table;

    /** @var TableGateway */
    private $tableGateway;

    /**
     * TableRepository constructor.
     *
     * @param Connection $connection
     * @param Table $table
     */
    public function __construct(Connection $connection, Table $table)
    {
        $this->connection = $connection;
        $this->table = $table;

        $this->tableGateway = new TableGateway($connection, $table);
    }

    /**
     * @param array $data
     *
     * @return EntityInterface
     */
    public function newEntity(array $data = []): EntityInterface
    {
        return new MutableEntity($this->getEntityData($data));
    }

    /**
     * @param array $data
     *
     * @return EntityInterface
     */
    public function newImmutableEntity(array $data): EntityInterface
    {
        return new ImmutableEntity($this->getEntityData($data));
    }

    /**
     * @return EventManager
     */
    public function getEventManager(): EventManager
    {
        // @TODO add events to methods
        return $this->connection->getEventManager();
    }

    /**
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(string $alias = null): QueryBuilder
    {
        $queryBuilder = new QueryBuilder($this->connection);

        $queryBuilder->from($this->table->getName(), $alias);

        return $queryBuilder;
    }

    /**
     * @param $primaryKey
     *
     * @return EntityInterface|null
     */
    public function find($primaryKey): ?EntityInterface
    {
        $data = $this->tableGateway->find($primaryKey);

        return $data ? $this->newImmutableEntity($data) : null;
    }

    public function findAll()
    {

    }

    /**
     * @param EntityInterface $entity
     *
     * @return ImmutableEntity
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function create(EntityInterface $entity): ImmutableEntity
    {
        $this->getEventManager()->dispatchEvent('before:create');

        $affectedRows = $this->connection->insert(
            $this->table->getName(),
            $entity->toArray()
        );

        //return $this->connection->lastInsertId();
        // @TODO return find($this->connection->lastInsertId());
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getEntityData(array $data = []): array
    {
        $attributes = [];

        foreach ($this->table->getColumns() as $column) {
            $name = $column->getName();
            $type = $column->getType();

            $attributes[$name] = array_key_exists($name, $data) ?
                $type->convertToPHPValue($data[$name], $this->connection->getDatabasePlatform()) :
                $column->getDefault();
        }

        foreach ($this->table->getForeignKeys() as $foreignKey) {
            $name = $foreignKey->getName();
            //$foreignKey->get();
        }

        return $attributes;
    }
}