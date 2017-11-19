<?php

namespace Bab\Datagen\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Bab\Datagen\Fixture;
use Bab\Datagen\Tests\Stub;

class FixtureTest extends TestCase
{
    public function test_basic_fixture()
    {
        $fixture = new Stub\BasicFixture();

        $this->assertSame('basic_stub_fixture', $fixture->getTableName());
    }

    /**
     * @expectedException Bab\Datagen\Exception\NoTableNameDefinedException
     * @expectedExceptionMessage No tableName specified for "Bab\Datagen\Tests\Stub\WithoutTableNameFixture". Please define $tableName static property.
     */
    public function test_fixture_without_name()
    {
        $fixture = new Stub\WithoutTableNameFixture();

        $this->assertSame('basic_stub_fixture', $fixture->getTableName());
    }
}
