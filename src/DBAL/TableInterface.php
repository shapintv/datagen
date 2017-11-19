<?php

declare(strict_types=1);

namespace Bab\Datagen\DBAL;

use Doctrine\DBAL\Schema\Schema;

interface TableInterface
{
    public function addTableToSchema(Schema $schema);

    public static function getOrder(): int;
}
