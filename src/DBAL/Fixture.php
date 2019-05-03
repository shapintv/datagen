<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Shapin\Datagen\Exception\NoTableNameDefinedException;

abstract class Fixture implements FixtureInterface
{
    protected static $tableName;
    protected static $order = 50;

    abstract public function getRows(): array;

    public static function getTableName(): string
    {
        if (null === static::$tableName) {
            throw new NoTableNameDefinedException(static::class);
        }

        return static::$tableName;
    }

    public static function getOrder(): int
    {
        return static::$order;
    }
}
