<?php

namespace Rucola;

use Rucola\Util\DataMapper;

class Rucola
{
    use Mapping, Optional, Multiple, Type;

    public function __construct()
    {
        $this->dataMapper = new DataMapper();
    }
}
