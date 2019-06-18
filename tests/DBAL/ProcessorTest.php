<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\DBAL\Processor;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen as Table;
use Shapin\Datagen\DBAL\Processor;

class ProcessorTest extends TestCase
{
    private $connection;
    private $processor;

    public function setUp(): void
    {
        $this->connection = $this->prophesize(Connection::class);
        $this->connection->getDatabasePlatform()->willReturn(new SqlitePlatform());

        $this->processor = new Processor($this->connection->reveal());
    }

    public function testSchemaAndFixtures()
    {
        $this->connection->query('CREATE TABLE table1 (uuid VARCHAR(255) NOT NULL, field1 VARCHAR(50) DEFAULT NULL, created_at BIGINT UNSIGNED DEFAULT NULL, PRIMARY KEY(uuid))')->shouldBeCalledTimes(1);
        $this->connection->query('CREATE INDEX IDX_1C95229DE637C334 ON table1 (field1)')->shouldBeCalledTimes(1);
        $this->connection->insert('table1', Argument::type('array'), ['created_at' => 'datetimetz'])->shouldBeCalledTimes(3);

        $this->processor->process(new Table\Table1());
        $this->processor->flush();
    }

    public function testSchemaOnly()
    {
        $this->connection->query('CREATE TABLE table1 (uuid VARCHAR(255) NOT NULL, field1 VARCHAR(50) DEFAULT NULL, created_at BIGINT UNSIGNED DEFAULT NULL, PRIMARY KEY(uuid))')->shouldBeCalledTimes(1);
        $this->connection->query('CREATE INDEX IDX_1C95229DE637C334 ON table1 (field1)')->shouldBeCalledTimes(1);

        $options = ['schema_only' => true];
        $this->processor->process(new Table\Table1(), $options);
        $this->processor->flush($options);
    }

    public function testFixturesOnly()
    {
        $this->connection->insert('table1', Argument::type('array'), ['created_at' => 'datetimetz'])->shouldBeCalledTimes(3);

        $options = ['fixtures_only' => true];
        $this->processor->process(new Table\Table1(), $options);
        $this->processor->flush($options);
    }
}
