<?php

declare(strict_types=1);

namespace Shapin\Datagen\Stripe\Exception;

class NoObjectNameDefinedException extends \Exception
{
    public function __construct($className)
    {
        parent::__construct("No objectName specified for \"$className\". Please define \$objectName static property.");
    }
}
