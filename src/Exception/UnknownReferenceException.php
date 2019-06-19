<?php

declare(strict_types=1);

namespace Shapin\Datagen\Exception;

class UnknownReferenceException extends \Exception
{
    public function __construct(string $fixture, string $name)
    {
        parent::__construct("Unknown reference \"$name\" for fixture \"$fixture\".");
    }
}
