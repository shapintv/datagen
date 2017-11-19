<?php

namespace Bab\SimpleFixtures\Exception;

class NoTableNameDefinedException extends \Exception
{
    public function __construct($className)
    {
        parent::__construct("No tableName specified for \"$className\". Please define \$tableName static property.");
    }
}
