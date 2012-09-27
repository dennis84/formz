<?php

namespace Rucula;

use Rucula\Type\TypeInterface;
use Rucula\Type\MultipleType;

trait Multiple
{
    public function multiple($typeOfField)
    {
        if ($typeOfField instanceof TypeInterface) {
            return new MultipleType($typeOfField);
        }
           
        if ($typeOfField instanceof Field) {
            return $typeOfField;
        }

        throw new \InvalidArgumentException('You must pass an instance of TypeInterface of Field to multiple method.');
    }
}
