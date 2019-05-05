<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\Command;

use Shapin\Datagen\DBAL\Loader\SchemaLoader;
use Shapin\Datagen\DBAL\Loader\FixtureLoader;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DBALSchemaCreateCommand extends Command
{
    private $groups;
    private $fixtureLoader;
    private $connection;

    public function __construct(Connection $connection, FixtureLoader $fixtureLoader, array $groups)
    {
        $this->connection = $connection;
        $this->fixtureLoader = $fixtureLoader;
        $this->groups = $groups;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('datagen:dbal:schema:create')
            ->setDescription('Create the DBAL schema.')
            ->addOption('groups', 'g', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Which groups should be loaded? (default: all)')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Create database schema with DBAL.');

        $groups = $this->groups;
        if ([] !== $wantedGroups = $input->getOption('groups')) {
            $groups = [];
            foreach ($wantedGroups as $wantedGroup) {
                if (!array_key_exists($wantedGroup, $this->groups)) {
                    throw new \InvalidArgumentException(sprintf('Unknown group "%s". Available: ["%s"]', $wantedGroup, implode('", "', array_keys($this->groups))));
                }

                $groups[$wantedGroup] = $this->groups[$wantedGroup];
            }
        }

        $schemaLoader = new SchemaLoader(new Schema());
        foreach ($groups as $path) {
            $schemaLoader->load($path);
        }

        $statements = $schemaLoader->getSchema()->toSql($this->connection->getDatabasePlatform());
        foreach ($statements as $statement) {
            $this->connection->query($statement);
        }

        $io->success('Schema created successfully.');

        foreach ($groups as $path) {
            $this->fixtureLoader->load($path);
        }

        foreach ($this->fixtureLoader->getFixtures() as $fixture) {
            $this->connection->insert($fixture[0], $fixture[1]);
        }

        $io->success('Fixtures created successfully.');
    }
}
