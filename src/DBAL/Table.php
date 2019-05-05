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

    public static function getOrder(): int
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

    public function getRows(): array
    {
        return [];
    }
}
