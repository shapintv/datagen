<?php

declare(strict_types=1);

namespace Shapin\Datagen\Exception;

class NoTableNameDefinedException extends \Exception
{
    public function __construct($className)
    {
        parent::__construct("No tableName specified for \"$className\". Please define \$tableName static property.");
    }
}
