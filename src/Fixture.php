<?php

declare(strict_types=1);

namespace Shapin\Datagen;

abstract class Fixture implements FixtureInterface
{
    protected static $order = 50;

    public function getOrder(): int
    {
        return static::$order;
    }
}
