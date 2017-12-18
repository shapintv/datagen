<?php

declare(strict_types=1);

namespace Bab\Datagen\Bridge\Symfony\Bundle\Command;

use Bab\Datagen\DBAL\Loader\FixtureLoader;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DBALFixturesLoadCommand extends ContainerAwareCommand
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

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

        $rootDir = $this->getContainer()->getParameter('kernel.root_dir');
        $bundles = $this->getContainer()->getParameter('kernel.bundles');
        $paths = [$rootDir.'/Datagen/DBAL/Fixtures'];
        foreach ($bundles as $name => $path) {
            $paths[] = $path.'/Datagen/DBAL/Fixtures';
        }

        $fixtureLoader = new FixtureLoader();
        foreach ($paths as $path) {
            $fixtureLoader->load($path);
        }

        foreach ($fixtureLoader->getFixtures() as $fixture) {
            $this->connection->insert($fixture[0], $fixture[1]);
        }

        $io->success('Fixtures created successfully.');
    }
}
