<?php

declare(strict_types=1);

namespace Bab\Datagen\Tests\Fixtures\TestBundle\Datagen\DBAL\Fixtures;

use Bab\Datagen\DBAL\Fixture;

class Table3 extends Fixture
{
    protected static $tableName = 'table3';
    protected static $order = 30;

    public function getRows(): array
    {
        return [
            ['uuid' => 'uuid3_1', 'field3' => 'another_field'],
            ['uuid' => 'uuid3_2', 'field3' => 'another_field'],
            ['uuid' => 'uuid3_3', 'field3' => 'another_field'],
        ];
    }
}
