<?php

declare(strict_types=1);

namespace Shapin\Datagen;

class FixtureSorter
{
    public function sort(array $fixtures): array
    {
        // Order all tables
        usort($fixtures, function ($a, $b) {
            if (!$a instanceof FixtureInterface || !$b instanceof FixtureInterface) {
                throw new \InvalidArgumentException('Unable to sort fixtures which do not implement OrderedFixture.');
            }

            if ($a->getOrder() === $b->getOrder()) {
                return 0;
            }

            return $a->getOrder() < $b->getOrder() ? -1 : 1;
        });

        return $fixtures;
    }
}
