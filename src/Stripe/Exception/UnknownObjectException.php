<?php

declare(strict_types=1);

namespace Shapin\Datagen\Stripe\Exception;

class UnknownObjectException extends \Exception
{
    public function __construct($objectName)
    {
        parent::__construct("Unknown object \"$objectName\".");
    }
}
