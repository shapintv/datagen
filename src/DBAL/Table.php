<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Doctrine\DBAL\Schema\Schema;
use Shapin\Datagen\DBAL\Exception\NoTableNameDefinedException;

abstract class Table extends FixtureCollection
{
    protected static $tableName;

    abstract public function addTableToSchema(Schema $schema);

    public static function getTableName(): string
    {
        if (null === static::$tableName) {
            throw new NoTableNameDefinedException(static::class);
        }

        return static::$tableName;
    }

    public function getFixtures(): iterable
    {
        foreach ($this->getRows() as $key => $fields) {
            yield $key => new Fixture(self::getTableName(), $fields, $this->getTypes());
        }
    }

    public function getRows(): iterable
    {
        return [];
    }

    public function getTypes(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessor(): string
    {
        return 'dbal';
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::getTableName();
    }
}
