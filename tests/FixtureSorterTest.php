<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests;

use Shapin\Datagen\FixtureSorter;
use Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen\DBAL as Tables;
use PHPUnit\Framework\TestCase;

class FixtureSorterTest extends TestCase
{
    public function testOrder()
    {
        $tables = [
            new Tables\Table6(),
            new Tables\Table3(),
            new Tables\Table5(),
            new Tables\Table1(),
            new Tables\Table2(),
            new Tables\Table4(),
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
