<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests;

use Shapin\Datagen\Loader;
use Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen as Table;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    private $loader;

    public function setUp(): void
    {
        $this->loader = new Loader();
        $this->loader->addFixture(new Table\Table1(), ['group1']);
        $this->loader->addFixture(new Table\Table2(), ['group1']);
        $this->loader->addFixture(new Table\Table3(), ['group1']);
        $this->loader->addFixture(new Table\Table4(), ['group2']);
        $this->loader->addFixture(new Table\Table5(), ['group2']);
        $this->loader->addFixture(new Table\Table6(), ['group1', 'group2']);
    }

    public function test_getAll()
    {
        $fixtures = $this->loader->getFixtures();
        $this->assertCount(6, $fixtures);
    }

    public function test_getGroup()
    {
        $fixtures = $this->loader->getFixtures(['group1']);
        $this->assertCount(4, $fixtures);
    }

    public function test_excludeGroup()
    {
        $fixtures = $this->loader->getFixtures([], ['group1']);
        $this->assertCount(2, $fixtures);
    }

    public function testGetAndExcludeSameGroup()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('You can\'t both select & ignore a given group. Errored: ["group1"]');
        $this->loader->getFixtures(['group1'], ['group1']);
    }

    public function test_getUnknwonGroup()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown group "group42". Available: ["group1", "group2"]');
        $this->loader->getFixtures(['group42']);
    }
}
