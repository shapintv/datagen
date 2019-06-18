<?php

declare(strict_types=1);

namespace Shapin\Datagen;

interface ProcessorInterface
{
    public function getName(): string;

    public function process(FixtureInterface $fixture, array $options = []): void;

    public function flush(array $options = []): void;
}
