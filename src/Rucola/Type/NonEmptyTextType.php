<?php

namespace Rucola\Type;

use Rucola\Field;
use Rucola\Error;

class NonEmptyTextType extends TextType
{
    public function validate($value)
    {
        return '' !== $value ? true : false;
    }

    public function onInvalid(Field $field)
    {
        $field->addError(new Error('non_empty_text', 'The value must not be empty'));
    }

    public function getName()
    {
        return 'non_empty_text';
    }
}
