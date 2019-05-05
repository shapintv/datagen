<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen\DBAL\Table;

use Shapin\Datagen\DBAL\Table;
use Doctrine\DBAL\Schema\Schema;

class Table2 extends Table
{
    protected static $tableName = 'table2';
    protected static $order = 20;

    /**
     * {@inheritdoc}
     */
    public function addTableToSchema(Schema $schema)
    {
        $table = $schema->createTable(self::$tableName);

        $table->addColumn('uuid', 'string');
        $table->addColumn('field2', 'string', ['length' => 50, 'notnull' => false]);
        $table->addColumn('created_at', 'bigint', ['unsigned' => true, 'notnull' => false]);

        $table->setPrimaryKey(['uuid']);
        $table->addIndex(['field2']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        return [
            ['uuid' => 'uuid2_1'],
            ['uuid' => 'uuid2_2'],
            ['uuid' => 'uuid2_3'],
        ];
    }
}
