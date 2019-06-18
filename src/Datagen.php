<?php

declare(strict_types=1);

namespace Shapin\Datagen;

use Shapin\Exception\UnknwonProcessorException;

class Datagen
{
    private $loader;
    private $processors = [];

    public function __construct(Loader $loader, iterable $processors)
    {
        $this->loader = $loader;
        foreach ($processors as $processor) {
            $this->processors[$processor->getName()] = $processor;
        }
    }

    public function load(array $groups = [], array $excludeGroups = [], array $options = []): void
    {
        $fixtures = $this->loader->getFixtures($groups, $excludeGroups);

        foreach ($fixtures as $fixture) {
            $processorOptions = $options[$fixture->getProcessor()] ?: [];

            $this->getProcessor($fixture->getProcessor())->process($fixture, $processorOptions);
        }

        foreach ($this->processors as $processor) {
            $processor->flush($options[$fixture->getProcessor()] ?: []);
        }
    }

    private function getProcessor(string $name): ProcessorInterface
    {
        if (!array_key_exists($name, $this->processors)) {
            throw new UnknwonProcessorException($name);
        }

        return $this->processors[$name];
    }
}
