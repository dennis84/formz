<?php

namespace Rucula\Type;

use Rucula\Field;
use Rucula\Error;

class NonEmptyTextType extends TextType
{
    public function validate(Field $field)
    {
        parent::validate($field);

        if ('' === $field->getValue()) {
            $field->addError(new Error('The value must not be empty'));
        }
    }
}
