<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\DBAL\Loader;

use Shapin\Datagen\DBAL\Loader\FixtureLoader;
use PHPUnit\Framework\TestCase;

class FixtureLoaderTest extends TestCase
{
    public function test_basic_fixture()
    {
        $fixtureLoader = new FixtureLoader();

        $fixtureLoader->load(__DIR__.'/../../Fixtures/TestBundle/Datagen/DBAL');

        $fixtures = $fixtureLoader->getFixtures();
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
}
