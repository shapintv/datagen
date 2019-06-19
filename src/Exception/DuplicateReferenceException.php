<?php

declare(strict_types=1);

namespace Shapin\Datagen\Exception;

class DuplicateReferenceException extends \Exception
{
    public function __construct(string $fixture, string $name)
    {
        parent::__construct("Duplicate reference \"$name\" for fixture \"$fixture\".");
    }
}
