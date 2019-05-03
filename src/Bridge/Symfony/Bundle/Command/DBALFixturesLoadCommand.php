<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\Command;

use Shapin\Datagen\DBAL\Loader\FixtureLoader;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->setDescription('Load fixtures in database using DBAL.')
            ->addOption('groups', 'g', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Which groups should be loaded? (default: all)')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Load fixtures in database using DBAL.');

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

        $fixtureLoader = new FixtureLoader();
        foreach ($groups as $name => $path) {
            $fixtureLoader->load($path);
        }

        foreach ($fixtureLoader->getFixtures() as $fixture) {
            $this->connection->insert($fixture[0], $fixture[1]);
        }

        $io->success('Fixtures created successfully.');
    }
}
