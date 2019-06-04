<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\Command;

use Shapin\Datagen\DBAL\Loader;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DBALLoadCommand extends Command
{
    private $connection;
    private $loader;

    public function __construct(Connection $connection, Loader $loader)
    {
        $this->connection = $connection;
        $this->loader = $loader;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('shapin:datagen:dbal:load')
            ->setDescription('Load the DBAL schema.')
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

        $groups = $input->getOption('groups', []);

        $statements = $this->loader->getSchema($groups)->toSql($this->connection->getDatabasePlatform());
        foreach ($statements as $statement) {
            $this->connection->query($statement);
        }

        $io->success('Schema created successfully.');

        foreach ($this->loader->getFixtures($groups) as $fixture) {
            $this->connection->insert($fixture[0], $fixture[1], $fixture[2]);
        }

        $io->success('Fixtures created successfully.');
    }
}
