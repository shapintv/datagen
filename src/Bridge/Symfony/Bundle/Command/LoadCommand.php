<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\Command;

use Shapin\Datagen\Datagen;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadCommand extends Command
{
    private $datagen;

    public function __construct(Datagen $datagen)
    {
        $this->datagen = $datagen;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('shapin:datagen:load')
            ->setDescription('Load the world!')
            ->addOption('group', 'g', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Which groups should be loaded? (default: all)')
            ->addOption('exclude-group', 'G', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Which groups should be excluded? (default: none)')
            ->addOption('dbal-schema-only', null, InputOption::VALUE_NONE, '[DBAL] Load only schema.')
            ->addOption('dbal-fixtures-only', null, InputOption::VALUE_NONE, '[DBAL] Load only fixtures.')
            ->addOption('processor', 'p', InputOption::VALUE_REQUIRED, 'Load only fixtures related to given processor.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Load the world! \o/');

        $groups = $input->getOption('group');
        $excludeGroups = $input->getOption('exclude-group');

        $options = [
            'dbal' => [
                'schema_only' => $input->getOption('dbal-schema-only'),
                'fixtures_only' => $input->getOption('dbal-fixtures-only'),
            ],
        ];
        if (null !== $processor = $input->getOption('processor')) {
            $options['processor'] = $processor;
        }

        $this->datagen->load($groups, $excludeGroups, $options);

        $io->success('Job DONE!');

        return 0;
    }
}
