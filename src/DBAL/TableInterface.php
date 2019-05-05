<?php

declare(strict_types=1);

namespace Shapin\Datagen\DBAL;

use Doctrine\DBAL\Schema\Schema;

interface TableInterface
{
    public function addTableToSchema(Schema $schema);

    public static function getOrder(): int;

    public static function getTableName(): string;

    public function getRows(): array;
}
