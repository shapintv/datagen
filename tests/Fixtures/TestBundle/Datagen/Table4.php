<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen;

use Doctrine\DBAL\Schema\Schema;
use Shapin\Datagen\DBAL\Table;

class Table4 extends Table
{
    protected static $tableName = 'table4';

    /**
     * {@inheritdoc}
     */
    public function addTableToSchema(Schema $schema)
    {
        $table = $schema->createTable('table4');

        $table->addColumn('uuid', 'string');
        $table->addColumn('field4', 'string', ['length' => 50]);
        $table->addColumn('created_at', 'bigint', ['unsigned' => true]);

        $table->setPrimaryKey(['uuid']);
        $table->addIndex(['field4']);
    }
}
