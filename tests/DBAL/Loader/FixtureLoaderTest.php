<?php

declare(strict_types=1);

namespace Bab\Datagen\Tests\DBAL\Loader;

use Bab\Datagen\DBAL\Loader\FixtureLoader;
use PHPUnit\Framework\TestCase;

class FixtureLoaderTest extends TestCase
{
    public function test_basic_fixture()
    {
        $fixtureLoader = new FixtureLoader();

        $fixtureLoader->load(__DIR__ . '/../../Fixtures/TestBundle/Datagen/DBAL/Fixtures');

        $fixtures = $fixtureLoader->getFixtures();
        $this->assertCount(9, $fixtures);

        $expectedFixtures = [
            ['table1', ['uuid' => 'uuid1_1']],
            ['table1', ['uuid' => 'uuid1_2']],
            ['table1', ['uuid' => 'uuid1_3']],
            ['table3', ['uuid' => 'uuid3_1']],
            ['table3', ['uuid' => 'uuid3_2']],
            ['table3', ['uuid' => 'uuid3_3']],
            ['table2', ['uuid' => 'uuid2_1']],
            ['table2', ['uuid' => 'uuid2_2']],
            ['table2', ['uuid' => 'uuid2_3']],
        ];

        $this->assertEquals($expectedFixtures, $fixtures);
    }
}
