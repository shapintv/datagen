<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Shapin\Datagen\FixtureInterface;
use Doctrine\DBAL\Schema\Schema;

interface TableInterface extends FixtureInterface
{
    public function addTableToSchema(Schema $schema);

    public static function getTableName(): string;

    public function getRows(): iterable;

    public function getTypes(): array;
}
