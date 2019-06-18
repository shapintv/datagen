<?php

declare(strict_types=1);

namespace Shapin\Datagen\Stripe;

use Shapin\Datagen\FixtureInterface as BaseFixtureInterface;

interface FixtureInterface extends BaseFixtureInterface
{
    public function getObjectName(): string;

    public function getObjects(): iterable;
}
