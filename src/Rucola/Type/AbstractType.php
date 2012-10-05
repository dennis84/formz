<?php

namespace Rucola\Type;

use Rucola\Field;

abstract class AbstractType implements TypeInterface
{
    public function validate($value)
    {
        return true;
    }

    public function onValid(Field $field)
    {
    }

    public function onInvalid(Field $field)
    {
    }

    abstract public function getName();
}
