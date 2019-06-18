<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Doctrine\DBAL\Schema\Schema;
use Shapin\Datagen\Fixture;
use Shapin\Datagen\DBAL\Exception\NoTableNameDefinedException;

abstract class Table extends Fixture implements TableInterface
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

    public function getRows(): iterable
    {
        return [];
    }

    public function getTypes(): array
    {
        return [];
    }

    public function getProcessor(): string
    {
        return 'dbal';
    }

    public function getName(): string
    {
        return self::getTableName();
    }
}
