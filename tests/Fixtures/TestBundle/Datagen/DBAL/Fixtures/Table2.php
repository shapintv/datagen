<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Fixtures\TestBundle\Datagen\DBAL\Fixtures;

use Shapin\Datagen\DBAL\Fixture;

class Table2 extends Fixture
{
    protected static $tableName = 'table2';

    public function getRows(): array
    {
        return [
            ['uuid' => 'uuid2_1'],
            ['uuid' => 'uuid2_2'],
            ['uuid' => 'uuid2_3'],
        ];
    }
}
