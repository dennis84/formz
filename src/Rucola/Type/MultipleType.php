<?php

namespace Rucola\Type;

use Rucola\Field;
use Rucola\Error;

class MultipleType extends AbstractType
{
    protected $baseType;

    public function __construct(TypeInterface $baseType)
    {
        $this->baseType = $baseType;
    }

    public function validate($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('The data bind to the multiple field must be an array.');
        }

        foreach ($value as $choice) {
            $this->baseType->validate($choice);
        }
    }

    public function getName()
    {
        return 'multiple';
    }
}
