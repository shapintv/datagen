<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\DBAL\Loader;

use Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen\DBAL as Table;
use Shapin\Datagen\DBAL\Loader;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    public function test_get_schema()
    {
        $tables = $this->getLoader()->getSchema()->getTables();

        $this->assertCount(6, $tables);

        $tableNames = [];
        array_walk($tables, function ($item, $key) use (&$tableNames) {
            $tableNames[] = $item->getName();
        });

        $this->assertEquals(['table1', 'table2', 'table3', 'table6', 'table4', 'table5'], $tableNames);
    }

    public function test_getFixtures()
    {
        $fixtures = iterator_to_array($this->getLoader()->getFixtures());
        $this->assertCount(9, $fixtures);

        $date = new \Datetime('@0');

        $expectedFixtures = [
            ['table1', ['uuid' => 'uuid1_1', 'created_at' => $date], ['created_at' => 'datetimetz']],
            ['table1', ['uuid' => 'uuid1_2', 'created_at' => $date], ['created_at' => 'datetimetz']],
            ['table1', ['uuid' => 'uuid1_3', 'created_at' => $date], ['created_at' => 'datetimetz']],
            ['table2', ['uuid' => 'uuid2_1'], []],
            ['table2', ['uuid' => 'uuid2_2'], []],
            ['table2', ['uuid' => 'uuid2_3'], []],
            ['table3', ['uuid' => 'uuid3_1', 'field3' => 'another_field'], []],
            ['table3', ['uuid' => 'uuid3_2', 'field3' => 'another_field'], []],
            ['table3', ['uuid' => 'uuid3_3', 'field3' => 'another_field'], []],
        ];

        $this->assertEquals($expectedFixtures, $fixtures);
    }

    private function getLoader()
    {
        $loader = new Loader();
        $loader->addTable(new Table\Table1());
        $loader->addTable(new Table\Table2());
        $loader->addTable(new Table\Table3());
        $loader->addTable(new Table\Table4());
        $loader->addTable(new Table\Table5());
        $loader->addTable(new Table\Table6());

        return $loader;
    }
}
