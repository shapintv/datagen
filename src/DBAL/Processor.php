<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Doctrine\DBAL\Schema\Schema;
use Shapin\Datagen\Loader;

class Processor
{
    private $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    public function getSchema(array $groups = [], array $excludeGroups = []): Schema
    {
        $fixtures = $this->loader->getFixtures($groups, $excludeGroups);

        $schema = new Schema();

        foreach ($fixtures as $fixture) {
            if ($fixture instanceof TableInterface) {
                $fixture->addTableToSchema($schema);
            }
        }

        return $schema;
    }

    public function getFixtures(array $groups = [], array $excludeGroups = []): iterable
    {
        $fixtures = $this->loader->getFixtures($groups, $excludeGroups);

        foreach ($fixtures as $fixture) {
            if (!$fixture instanceof TableInterface) {
                continue;
            }

            $tableName = $fixture->getTableName();
            $types = $fixture->getTypes();

            foreach ($fixture->getRows() as $row) {
                yield [$tableName, $row, $types];
            }
        }
    }
}
