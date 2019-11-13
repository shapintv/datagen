<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Shapin\Datagen\Fixture as DatagenFixture;

abstract class FixtureCollection extends DatagenFixture
{
    /**
     * {@inheritdoc}
     */
    public function getProcessor(): string
    {
        return 'dbal';
    }

    abstract public function getFixtures(): iterable;
}
