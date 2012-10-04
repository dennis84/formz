<?php

namespace Rucula\Type;

use Rucula\Field;
use Rucula\Error;

class MultipleType extends AbstractType
{
    protected $baseType;

    public function __construct(TypeInterface $baseType)
    {
        $this->baseType = $baseType;
    }

    public function validate($value)
    {
        foreach ($value as $choice) {
            $this->baseType->validate($choice);
        }
    }

    public function getName()
    {
        return 'multiple';
    }
}
