<?php

namespace Rucola\Type;

use Rucola\Field;
use Rucola\Error;

class TextType extends AbstractType
{
    public function getName()
    {
        return 'text';
    }
}
