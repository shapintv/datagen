<?php

declare(strict_types=1);

namespace Shapin\Datagen\Bridge\Symfony\Bundle\Command;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Comparator;
use Shapin\Datagen\Bridge\Symfony\Console\Helper\DoctrineSchemaDiffHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatabaseDiffCommand extends Command
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
            ->setName('shapin:datagen:db:diff')
            ->setDescription('Compare a given schema with the configured one.')
            ->addArgument('dsn', InputArgument::REQUIRED, 'DSN of the schema to compare.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $platform = $this->connection->getDatabasePlatform();

        $remoteConnection = DriverManager::getConnection(['url' => $input->getArgument('dsn'), 'platform' => $platform], new Configuration());

        $localSchema = $this->connection->getSchemaManager()->createSchema();
        $remoteSchema = $remoteConnection->getSchemaManager()->createSchema();

        $schemaDiff = Comparator::compareSchemas($remoteSchema, $localSchema);

        $schemaDiffHelper = new DoctrineSchemaDiffHelper($output, $schemaDiff);
        $schemaDiffHelper->render();
    }
}
