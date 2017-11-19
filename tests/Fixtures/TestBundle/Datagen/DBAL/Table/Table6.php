<?php

namespace Bab\Datagen\Tests\Fixtures\TestBundle\Datagen\DBAL\Table;

use Bab\Datagen\DBAL\Table;
use Doctrine\DBAL\Schema\Schema;

class Table6 extends Table
{
    protected static $order = 40;

    /**
     * {@inheritdoc}
     */
    public function addTableToSchema(Schema $schema)
    {
        $table = $schema->createTable('table6');

        $table->addColumn('uuid', 'string');
        $table->addColumn('field6', 'string', ['length' => 50]);
        $table->addColumn('created_at', 'bigint', ['unsigned' => true]);

        $table->setPrimaryKey(['uuid']);
        $table->addIndex(['field6']);
    }
}
