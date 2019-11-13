<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Bridge\Symfony\Console\Helper;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaDiff;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use Shapin\Datagen\Bridge\Symfony\Console\Helper\DoctrineSchemaDiffHelper;
use Symfony\Component\Console\Output\BufferedOutput;

class DoctrineSchemaDiffHelperTest extends TestCase
{
    public function test()
    {
        $output = new BufferedOutput();

        (new DoctrineSchemaDiffHelper($output, $this->getSchemaDiff()))->render();

        $expectedOutput = <<<EOF
Removed tables:
 * a_removed_table
 * another_removed_table

New tables:
 * a_new_table

Table "table_with_new_fields" have been changed:
 + new_column_1, string, [notnull => false]
Table "table_with_removed_fields" have been changed:
 - removed_column_1, string
Table "table_with_updated_fields" have been changed:
 - updated_column_1, string
 + updated_column_1, string, [notnull => false]

EOF;

        $this->assertEquals($expectedOutput, $output->fetch());
    }

    private function getSchemaDiff(): SchemaDiff
    {
        return Comparator::compareSchemas(
            new Schema([
                new Table('a_removed_table'),
                new Table('another_removed_table'),
                new Table('untouched_table'),
                new Table('table_with_new_fields', [
                    new Column('existing_column', Type::getType('string')),
                ]),
                new Table('table_with_removed_fields', [
                    new Column('existing_column', Type::getType('string')),
                    new Column('removed_column_1', Type::getType('string')),
                ]),
                new Table('table_with_updated_fields', [
                    new Column('updated_column_1', Type::getType('string')),
                ]),
            ]),
            new Schema([
                new Table('a_new_table'),
                new Table('untouched_table'),
                new Table('table_with_new_fields', [
                    new Column('existing_column', Type::getType('string')),
                    new Column('new_column_1', Type::getType('string'), ['notnull' => false]),
                ]),
                new Table('table_with_removed_fields', [
                    new Column('existing_column', Type::getType('string')),
                ]),
                new Table('table_with_updated_fields', [
                    new Column('updated_column_1', Type::getType('string'), ['notnull' => false]),
                ]),
            ])
        );
    }
}
