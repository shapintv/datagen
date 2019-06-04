<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen\DBAL;

use Shapin\Datagen\DBAL\Table;
use Doctrine\DBAL\Schema\Schema;

class Table1 extends Table
{
    protected static $tableName = 'table1';
    protected static $order = 10;

    /**
     * {@inheritdoc}
     */
    public function addTableToSchema(Schema $schema)
    {
        $table = $schema->createTable(self::$tableName);

        $table->addColumn('uuid', 'string');
        $table->addColumn('field1', 'string', ['length' => 50, 'notnull' => false]);
        $table->addColumn('created_at', 'bigint', ['unsigned' => true, 'notnull' => false]);

        $table->setPrimaryKey(['uuid']);
        $table->addIndex(['field1']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): iterable
    {
        $date = new \Datetime('@0');
        yield ['uuid' => 'uuid1_1', 'created_at' => $date];
        yield ['uuid' => 'uuid1_2', 'created_at' => $date];
        yield ['uuid' => 'uuid1_3', 'created_at' => $date];
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes(): array
    {
        return [
            'created_at' => 'datetimetz',
        ];
    }
}
