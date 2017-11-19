<?php

declare(strict_types=1);

namespace Bab\Datagen\Tests\Fixtures;

use Bab\Datagen\Fixture;

class BasicFixture extends Fixture
{
    protected static $tableName = 'basic_stub_fixture';

    public function getData(): array
    {
        return [
            ['row1', 'columnA', 2, 3, 123.30],
            ['row2', 'columnAbis', 2, 3, 113.30],
        ];
    }
}
