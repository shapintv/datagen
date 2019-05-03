<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Doctrine\DBAL\Schema\Schema;

abstract class Table implements TableInterface
{
    protected static $order = 50;

    abstract public function addTableToSchema(Schema $schema);

    public static function getOrder(): int
    {
        return static::$order;
    }
}
