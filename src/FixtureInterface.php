<?php

declare(strict_types=1);

namespace Shapin\Datagen;

interface FixtureInterface
{
    /**
     * Name of this fixture.
     */
    public function getName(): string;

    /**
     * Fixture order. Fixtures are loaded according to this value.
     */
    public function getOrder(): int;

    /**
     * The name of the processor in charge of loading this fixture.
     */
    public function getProcessor(): string;
}
