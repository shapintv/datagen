<?php

declare(strict_types=1);

namespace Shapin\Datagen\Stripe;

use Shapin\Datagen\Fixture as BaseFixture;

abstract class Fixture extends BaseFixture implements FixtureInterface
{
    protected static $objectName;

    public function getObjectName(): string
    {
        if (null === static::$objectName) {
            throw new NoObjectNameDefinedException(static::class);
        }

        return static::$objectName;
    }

    public function getProcessor(): string
    {
        return 'stripe';
    }

    public function getName(): string
    {
        return $this->getObjectName();
    }
}
