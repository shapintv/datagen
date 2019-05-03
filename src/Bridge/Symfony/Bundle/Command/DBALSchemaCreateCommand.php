<?php

declare(strict_types=1);

namespace Bab\Datagen\Bridge\Symfony\Bundle\Command;

use Bab\Datagen\DBAL\Loader\SchemaLoader;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DBALSchemaCreateCommand extends Command
{
    private $groups;
    private $connection;

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
            ->setName('datagen:dbal:schema:create')
            ->setDescription('Create the DBAL schema.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Create database schema with DBAL.');

        $schemaLoader = new SchemaLoader(new Schema());
        foreach ($this->groups as $path) {
            $schemaLoader->load($path);
        }

        $statements = $schemaLoader->getSchema()->toSql($this->connection->getDatabasePlatform());
        foreach ($statements as $statement) {
            $this->connection->query($statement);
        }

        $io->success('Schema created successfully.');
    }
}
