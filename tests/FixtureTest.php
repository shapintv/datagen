<?php

namespace Bab\SimpleFixtures\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Bab\SimpleFixtures\Fixture;
use Bab\SimpleFixtures\Tests\Stub;

class FixtureTest extends TestCase
{
    public function test_basic_fixture()
    {
        $fixture = new Stub\BasicFixture();

        $this->assertSame('basic_stub_fixture', $fixture->getTableName());
    }

    /**
     * @expectedException Bab\SimpleFixtures\Exception\NoTableNameDefinedException
     * @expectedExceptionMessage No tableName specified for "Bab\SimpleFixtures\Tests\Stub\WithoutTableNameFixture". Please define $tableName static property.
     */
    public function test_fixture_without_name()
    {
        $fixture = new Stub\WithoutTableNameFixture();

        $this->assertSame('basic_stub_fixture', $fixture->getTableName());
    }
}
