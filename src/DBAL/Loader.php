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
            if (!in_array($table->getTableName(), $this->groups[$group])) {
                $this->groups[$group][] = $table->getTableName();
            }
        }
    }

    public function getSchema(array $groups = [], array $excludeGroups = []): Schema
    {
        $schema = new Schema();

        foreach ($this->getTables($groups, $excludeGroups) as $table) {
            $table->addTableToSchema($schema);
        }

        return $schema;
    }

    public function getFixtures(array $groups = [], array $excludeGroups = []): iterable
    {
        foreach ($this->getTables($groups, $excludeGroups) as $table) {
            $tableName = $table->getTableName();
            $types = $table->getTypes();

            foreach ($table->getRows() as $row) {
                yield [$tableName, $row, $types];
            }
        }
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    private function getTables(array $groups, array $excludeGroups): array
    {
        $duplicatedGroups = array_intersect($groups, $excludeGroups);
        if (0 < count($duplicatedGroups)) {
            throw new \InvalidArgumentException(sprintf('You can\'t both select & ignore a given group. Errored: ["%s"]', implode('", "', $duplicatedGroups)));
        }

        // Check that all groups exists.
        foreach ($groups + $excludeGroups as $group) {
            if (!isset($this->groups[$group])) {
                throw new \InvalidArgumentException(sprintf('Unknown group "%s". Available: ["%s"]', $group, implode('", "', array_keys($this->groups))));
            }
        }

        // Select all relevant tables according to asked groups
        if (0 === count($groups)) {
            $tables = $this->tables;
        } else {
            $tables = [];
            foreach ($groups as $group) {
                foreach ($this->groups[$group] as $tableInGroup) {
                    $tables[$tableInGroup] = $this->tables[$tableInGroup];
                }
            }
        }

        // Remove all tables to exclude
        foreach ($excludeGroups as $excludeGroup) {
            foreach ($this->groups[$excludeGroup] as $tableToExclude) {
                if (array_key_exists($tableToExclude, $tables)) {
                    unset($tables[$tableToExclude]);
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
