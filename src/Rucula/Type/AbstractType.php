<?php

namespace Rucula\Type;

use Rucula\Field;

abstract class AbstractType implements TypeInterface
{
    abstract public function validate(Field $field);
}
