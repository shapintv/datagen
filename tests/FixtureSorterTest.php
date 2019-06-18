<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests;

use Shapin\Datagen\FixtureSorter;
use Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen as Table;
use PHPUnit\Framework\TestCase;

class FixtureSorterTest extends TestCase
{
    public function testOrder()
    {
        $tables = [
            new Table\Table6(),
            new Table\Table3(),
            new Table\Table5(),
            new Table\Table1(),
            new Table\Table2(),
            new Table\Table4(),
        ];
        $sortedTables = (new FixtureSorter())->sort($tables);

        $this->assertSame('table1', $sortedTables[0]::getTableName());
        $this->assertSame('table2', $sortedTables[1]::getTableName());
        $this->assertSame('table3', $sortedTables[2]::getTableName());
        $this->assertSame('table6', $sortedTables[3]::getTableName());
        $this->assertSame('table5', $sortedTables[4]::getTableName());
        $this->assertSame('table4', $sortedTables[5]::getTableName());
    }
}
