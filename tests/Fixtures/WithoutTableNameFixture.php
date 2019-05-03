<?php

declare(strict_types=1);

namespace Shapin\Datagen\Tests\Fixtures;

use Shapin\Datagen\DBAL\Fixture;

class WithoutTableNameFixture extends Fixture
{
    public function getRows(): array
    {
        return [
            ['row1', 'columnA', 2, 3, 123.30],
            ['row2', 'columnAbis', 2, 3, 113.30],
        ];
    }
}
