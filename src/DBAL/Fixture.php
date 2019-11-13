<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

class Fixture
{
    private $tableName;
    private $fields;
    private $types = [];

    public function __construct(string $tableName, array $fields, array $types = [])
    {
        $this->tableName = $tableName;
        $this->fields = $fields;
        $this->types = $types;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getTypes(): array
    {
        return $this->types;
    }
}
