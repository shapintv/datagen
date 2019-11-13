<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Shapin\Datagen\FixtureInterface;
use Shapin\Datagen\ProcessorInterface;
use Shapin\Datagen\ReferenceManager;

class Processor implements ProcessorInterface
{
    private $connection;
    private $referenceManager;
    private $schema;

    private $fixturesToLoad = [];

    public function __construct(Connection $connection, ReferenceManager $referenceManager)
    {
        $this->connection = $connection;
        $this->referenceManager = $referenceManager;
        $this->schema = new Schema();
    }

    /**
     * {@inheritdoc}
     */
    public function process(FixtureInterface $fixture, array $options = []): void
    {
        if ($fixture instanceof Table && !$this->resolveOption($options, 'fixtures_only', false)) {
            $fixture->addTableToSchema($this->schema);
        }

        if ($fixture instanceof FixtureCollection && !$this->resolveOption($options, 'schema_only', false)) {
            $this->fixturesToLoad[] = $fixture;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flush(array $options = []): void
    {
        if (!$this->resolveOption($options, 'fixtures_only', false)) {
            $statements = $this->schema->toSql($this->connection->getDatabasePlatform());
            foreach ($statements as $statement) {
                $this->connection->query($statement);
            }

            // Reset schema
            $this->schema = new Schema();
        }

        if ($this->resolveOption($options, 'schema_only', false)) {
            return;
        }

        foreach ($this->fixturesToLoad as $fixtureCollection) {
            foreach ($fixtureCollection->getFixtures() as $key => $fixture) {
                $fields = $this->referenceManager->findAndReplace($fixture->getFields());

                $this->connection->insert($fixture->getTableName(), $fields, $fixture->getTypes());

                if (is_string($key)) {
                    $this->referenceManager->add($fixture->getTableName(), $key, $fields);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'dbal';
    }

    private function resolveOption(array $options, string $key, $defaultValue = null)
    {
        if (!array_key_exists($key, $options)) {
            return $defaultValue;
        }

        return $options[$key];
    }
}
