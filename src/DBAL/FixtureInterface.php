<?php

declare(strict_types=1);

namespace Bab\Datagen\DBAL;

interface FixtureInterface
{
    public function getRows(): array;

    public static function getTableName(): string;

    public static function getOrder(): int;
}
