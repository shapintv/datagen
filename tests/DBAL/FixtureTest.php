<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\DBAL;

use PHPUnit\Framework\TestCase;
use Shapin\Datagen\Tests\Fixtures;

class FixtureTest extends TestCase
{
    public function test_basic_fixture()
    {
        $fixture = new Fixtures\BasicFixture();

        $this->assertSame('basic_stub_fixture', $fixture->getTableName());
    }

    /**
     * @expectedException \Shapin\Datagen\Exception\NoTableNameDefinedException
     * @expectedExceptionMessage No tableName specified for "Shapin\Datagen\Tests\Fixtures\WithoutTableNameFixture". Please define $tableName static property.
     */
    public function test_fixture_without_name()
    {
        $fixture = new Fixtures\WithoutTableNameFixture();

        $this->assertSame('basic_stub_fixture', $fixture->getTableName());
    }
}
