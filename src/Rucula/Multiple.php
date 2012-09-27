<?php

namespace Rucula;

use Rucula\Type\TypeInterface;
use Rucula\Type\MultipleType;

trait Multiple
{
    public function multiple(TypeInterface $type)
    {
        return new MultipleType($type);
    }
}
