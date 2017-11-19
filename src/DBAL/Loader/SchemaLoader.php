<?php

declare(strict_types=1);

namespace Bab\Datagen\DBAL\Loader;

use Bab\Datagen\DBAL\TableInterface;
use Doctrine\DBAL\Schema\Schema;

/**
 * @see https://github.com/doctrine/data-fixtures/blob/master/lib/Doctrine/Common/DataFixtures/Loader.php
 */
class SchemaLoader
{
    /**
     * The file extension of table files.
     *
     * @var string
     */
    private $fileExtension = '.php';

    private $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function load($path)
    {
        if (is_dir($path)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
        } elseif (is_file($path)) {
            $iterator = new \ArrayIterator([new \SplFileInfo($fileName)]);
        } else {
            return;
        }

        $includedFiles = [];
        foreach ($iterator as $file) {
            if (($fileName = $file->getBasename($this->fileExtension)) == $file->getBasename()) {
                continue;
            }
            $sourceFile = realpath($file->getPathName());
            require_once $sourceFile;
            $includedFiles[] = $sourceFile;
        }

        $declared = get_declared_classes();
        // Make the declared classes order deterministic
        sort($declared);

        $tables = [];
        foreach ($declared as $className) {
            $reflClass = new \ReflectionClass($className);
            $sourceFile = $reflClass->getFileName();

            if (in_array($sourceFile, $includedFiles) && !$this->isTransient($className)) {
                if (!isset($tables[$className])) {
                    $tables[$className] = new $className();
                }
            }
        }

        usort($tables, function ($a, $b) {
            if ($a->getOrder() === $b->getOrder()) {
                return 0;
            }

            return $a->getOrder() < $b->getOrder() ? -1 : 1;
        });

        foreach ($tables as $table) {
            $table->addTableToSchema($this->schema);
        }
    }

    public function getSchema(): Schema
    {
        return $this->schema;
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

        return !$rc->implementsInterface(TableInterface::class);
    }
}
