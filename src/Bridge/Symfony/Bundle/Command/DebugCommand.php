<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\Command;

use Shapin\Datagen\Loader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Repartition of fixtures by groups.');

        foreach ($this->loader->getFixturesByGroups() as $group => $fixtures) {
            $content = [];
            foreach ($fixtures as $fixture) {
                $content[] = "<comment>{$fixture->getOrder()}</comment> - <info>[{$fixture->getProcessor()}]</info> - {$fixture->getName()}";
            }
            $io->section($group);
            $io->listing($content);
        }
    }
}
