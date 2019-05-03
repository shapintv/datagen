<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\DBAL\Loader;

use Shapin\Datagen\DBAL\Loader\SchemaLoader;
use Doctrine\DBAL\Schema\Schema;
use PHPUnit\Framework\TestCase;

class SchemaLoaderTest extends TestCase
{
    public function test_basic_fixture()
    {
        $schemaLoader = new SchemaLoader(new Schema());

        $schemaLoader->load(__DIR__.'/../../Fixtures/TestBundle/Datagen/DBAL/Table');

        $tables = $schemaLoader->getSchema()->getTables();

        $this->assertCount(6, $tables);

        $tableNames = [];
        array_walk($tables, function ($item, $key) use (&$tableNames) {
            $tableNames[] = $item->getName();
        });

        $this->assertEquals(['table1', 'table2', 'table3', 'table6', 'table4', 'table5'], $tableNames);
    }
}
