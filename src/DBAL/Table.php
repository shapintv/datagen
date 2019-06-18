<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Doctrine\DBAL\Schema\Schema;
use Shapin\Datagen\Exception\NoTableNameDefinedException;

abstract class Table implements TableInterface
{
    protected static $tableName;
    protected static $order = 50;

    abstract public function addTableToSchema(Schema $schema);

    public function getOrder(): int
    {
        return static::$order;
    }

    public static function getTableName(): string
    {
        if (null === static::$tableName) {
            throw new NoTableNameDefinedException(static::class);
        }

        return static::$tableName;
    }

    public function getRows(): iterable
    {
        return [];
    }

    public function getTypes(): array
    {
        return [];
    }
}
