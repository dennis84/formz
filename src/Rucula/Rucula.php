<?php

namespace Rucula;

use Rucula\Type\TypeInterface;

class Rucula
{
    use Mapping, Optional, Multiple, Type;

    public function __construct()
    {
        $this->dataMapper = new Util\DataMapper();
    }
}
