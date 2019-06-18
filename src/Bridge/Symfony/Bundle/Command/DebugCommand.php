<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\Command;

use Shapin\Datagen\Loader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugCommand extends Command
{
    private $connection;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('shapin:datagen:debug')
            ->setDescription('Display information about fixtures.')
            ->addOption('group', 'g', InputOption::VALUE_REQUIRED, 'List only fixtures from the given group.')
            ->addOption('grouped', null, InputOption::VALUE_NONE, 'Display fixtures by groups.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (null !== $group = $input->getOption('group')) {
            $fixtures = $this->loader->getFixtures([$group]);

            $io->title("Fixtures in group \"$group\".");
            $io->listing($this->getListing($fixtures));

            return;
        }

        if (false !== $input->getOption('grouped')) {
            $io->title('Repartition of fixtures by groups.');

            $fixturesByGroup = $this->loader->getFixturesByGroups();

            foreach ($fixturesByGroup as $group => $fixtures) {
                $io->section($group);
                $io->listing($this->getListing($fixtures));
            }

            return;
        }

        $io->title('All fixtures');

        $io->listing($this->getListing($this->loader->getFixtures()));
    }

    private function getListing(array $fixtures): array
    {
        $content = [];
        foreach ($fixtures as $fixture) {
            $order = str_pad((string) $fixture->getOrder(), 3, ' ', STR_PAD_LEFT);
            $content[] = "<comment>$order</comment> - <info>[{$fixture->getProcessor()}]</info> - {$fixture->getName()}";
        }

        return $content;
    }
}
