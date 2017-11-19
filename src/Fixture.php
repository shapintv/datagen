<?php

namespace Bab\Datagen;

use Bab\Datagen\Exception\NoTableNameDefinedException;

abstract class Fixture
{
    protected static $tableName;

    abstract public function getData(): array;

    public static function getTableName(): string
    {
        if (null === static::$tableName) {
            throw new NoTableNameDefinedException(static::class);
        }

        return static::$tableName;
    }
}
