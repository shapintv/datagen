<?php

declare(strict_types=1);

namespace Shapin\Datagen;

class Loader
{
    private $fixtureSorter;

    private $fixtures = [];
    private $groups = [];

    public function __construct(FixtureSorter $fixtureSorter = null)
    {
        $this->fixtureSorter = $fixtureSorter ?? new FixtureSorter();
    }

    public function addFixture(FixtureInterface $fixture, array $groups = []): void
    {
        $key = $this->getUniqueKey($fixture);

        $this->fixtures[$key] = $fixture;

        foreach ($groups as $group) {
            if (!isset($this->groups[$group])) {
                $this->groups[$group] = [];
            }
            if (!in_array($key, $this->groups[$group])) {
                $this->groups[$group][] = $key;
            }
        }
    }

    public function getFixturesByGroups(): array
    {
        $groups = [];

        foreach ($this->groups as $name => $fixtures) {
            $groupFixtures = [];

            foreach ($fixtures as $fixtureUniqueKey) {
                $groupFixtures[] = $this->fixtures[$fixtureUniqueKey];
            }

            $groups[$name] = $this->fixtureSorter->sort($groupFixtures);
        }

        return $groups;
    }

    public function getFixtures(array $groups = [], array $excludeGroups = []): array
    {
        $duplicatedGroups = array_intersect($groups, $excludeGroups);
        if (0 < count($duplicatedGroups)) {
            throw new \InvalidArgumentException(sprintf('You can\'t both select & ignore a given group. Errored: ["%s"]', implode('", "', $duplicatedGroups)));
        }

        // Check that all groups exists.
        foreach ($groups + $excludeGroups as $group) {
            if (!isset($this->groups[$group])) {
                throw new \InvalidArgumentException(sprintf('Unknown group "%s". Available: ["%s"]', $group, implode('", "', array_keys($this->groups))));
            }
        }

        // Select all relevant fixtures according to asked groups
        if (0 === count($groups)) {
            $fixtures = $this->fixtures;
        } else {
            $fixtures = [];
            foreach ($groups as $group) {
                foreach ($this->groups[$group] as $fixtureInGroup) {
                    $fixtures[$fixtureInGroup] = $this->fixtures[$fixtureInGroup];
                }
            }
        }

        // Remove all fixtures to exclude
        foreach ($excludeGroups as $excludeGroup) {
            foreach ($this->groups[$excludeGroup] as $fixtureToExclude) {
                if (array_key_exists($fixtureToExclude, $fixtures)) {
                    unset($fixtures[$fixtureToExclude]);
                }
            }
        }

        $fixtures = $this->fixtureSorter->sort($fixtures);

        return $fixtures;
    }

    private function getUniqueKey(FixtureInterface $fixture): string
    {
        return $fixture->getProcessor().'-'.$fixture->getName();
    }
}
