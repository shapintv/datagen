<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL\Loader;

use Shapin\Datagen\DBAL\FixtureInterface;

/**
 * @see https://github.com/doctrine/data-fixtures/blob/master/lib/Doctrine/Common/DataFixtures/Loader.php
 */
class FixtureLoader
{
    private $fixtures = [];

    public function load($path)
    {
        if (is_dir($path)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
        } elseif (is_file($path)) {
            $iterator = new \ArrayIterator([new \SplFileInfo($path)]);
        } else {
            return;
        }

        $includedFiles = [];
        foreach ($iterator as $file) {
            if (($fileName = $file->getBasename('.php')) === $file->getBasename()) {
                continue;
            }
            $sourceFile = realpath($file->getPathName());
            require_once $sourceFile;
            $includedFiles[] = $sourceFile;
        }

        $declared = get_declared_classes();
        // Make the declared classes order deterministic
        sort($declared);

        $fixtures = [];
        foreach ($declared as $className) {
            $reflClass = new \ReflectionClass($className);
            $sourceFile = $reflClass->getFileName();

            if (in_array($sourceFile, $includedFiles) && !$this->isTransient($className)) {
                if (!isset($fixtures[$className])) {
                    $fixtures[$className] = new $className();
                }
            }
        }

        usort($fixtures, function ($a, $b) {
            if ($a->getOrder() === $b->getOrder()) {
                return 0;
            }

            return $a->getOrder() < $b->getOrder() ? -1 : 1;
        });

        foreach ($fixtures as $fixture) {
            $tableName = $fixture->getTableName();

            foreach ($fixture->getRows() as $row) {
                $this->fixtures[] = [$tableName, $row];
            }
        }
    }

    public function getFixtures(): array
    {
        return $this->fixtures;
    }

    /**
     * Check if a given class is transient and should not be considered a
     * class.
     *
     * @return bool
     */
    protected function isTransient($className)
    {
        $rc = new \ReflectionClass($className);
        if ($rc->isAbstract()) {
            return true;
        }

        return !$rc->implementsInterface(FixtureInterface::class);
    }
}
