<?php

namespace Rucula\Type;

use Rucula\Field;

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
