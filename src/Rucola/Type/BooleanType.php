<?php

namespace Rucola\Type;

use Rucola\Field;
use Rucola\Error;

class BooleanType extends AbstractType
{
    public function validate($value)
    {
        return ('false' === $value || 'true' === $value) ? true : false;
    }

    public function onValid(Field $field)
    {
        $field->setValue('true' === $field->getValue());
    }

    public function onInvalid(Field $field)
    {
        $field->addError(new Error('boolean', 'The value must be true or false.'));
    }

    public function getName()
    {
        return 'boolean';
    }
}
