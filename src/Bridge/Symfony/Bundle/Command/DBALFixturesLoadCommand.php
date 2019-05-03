<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\Command;

use Shapin\Datagen\DBAL\Loader\FixtureLoader;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DBALFixturesLoadCommand extends Command
{
    private $connection;
    private $groups;

    public function __construct(Connection $connection, array $groups)
    {
        $this->connection = $connection;
        $this->groups = $groups;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('datagen:dbal:fixtures:load')
            ->setDescription('Load fixtures in database using DBAL.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Load fixtures in database using DBAL.');

        $fixtureLoader = new FixtureLoader();
        foreach ($this->groups as $path) {
            $fixtureLoader->load($path);
        }

        foreach ($fixtureLoader->getFixtures() as $fixture) {
            $this->connection->insert($fixture[0], $fixture[1]);
        }

        $io->success('Fixtures created successfully.');
    }
}
