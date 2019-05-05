<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen\DBAL\Table;

use Shapin\Datagen\DBAL\Table;
use Doctrine\DBAL\Schema\Schema;

class Table5 extends Table
{
    protected static $tableName = 'table5';

    /**
     * {@inheritdoc}
     */
    public function addTableToSchema(Schema $schema)
    {
        $table = $schema->createTable('table5');

        $table->addColumn('uuid', 'string');
        $table->addColumn('field5', 'string', ['length' => 50]);
        $table->addColumn('created_at', 'bigint', ['unsigned' => true]);

        $table->setPrimaryKey(['uuid']);
        $table->addIndex(['field5']);
    }
}
