<?php

namespace Rucula\Type;

use Rucula\Field;
use Rucula\Error;

class TextType extends AbstractType
{
    public function validate(Field $field)
    {
        if (!is_string($field->getValue())) {
            $field->addError(new Error('The Value must me a string'));
        }
    }
}
