<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Doctrine\DBAL\Schema\Schema;

class Loader
{
    private $tables = [];
    private $groups = [];

    public function addTable(Table $table, array $groups = []): void
    {
        $this->tables[$table->getTableName()] = $table;

        foreach ($groups as $group) {
            if (!isset($this->groups[$group])) {
                $this->groups[$group] = [];
            }
            $this->groups[$group][] = $table->getTableName();
        }
    }

    public function getSchema(array $groups = []): Schema
    {
        $schema = new Schema();

        foreach ($this->getTables() as $table) {
            $table->addTableToSchema($schema);
        }

        return $schema;
    }

    public function getFixtures(array $groups = []): iterable
    {
        foreach ($this->getTables($groups) as $table) {
            $tableName = $table->getTableName();
            $types = $table->getTypes();

            foreach ($table->getRows() as $row) {
                yield [$tableName, $row, $types];
            }
        }
    }

    private function getTables(array $groups = []): array
    {
        // Check that all groups exists.
        foreach ($groups as $groups) {
            if (!isset($this->groups[$group])) {
                throw new \InvalidArgumentException(sprintf('Unknown group %s. Available: [%s]', $group, implode(', ', array_keys($this->groups))));
            }
        }

        if (0 === count($groups)) {
            $tables = $this->tables;
        } else {
            $tables = [];
            foreach ($groups as $group) {
                foreach ($this->groups[$group] as $tableInGroup) {
                    $tables[] = $this->tables[$tableInGroup];
                }
            }
        }

        // Order all tables
        usort($tables, function ($a, $b) {
            if ($a->getOrder() === $b->getOrder()) {
                return 0;
            }

            return $a->getOrder() < $b->getOrder() ? -1 : 1;
        });

        return $tables;
    }
}
