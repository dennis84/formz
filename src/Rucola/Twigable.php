<?php

namespace Rucola;

trait Twigable
{
    public function __get($name)
    {
        return $this->getChild($name);
    }

    public function __isset($name)
    {
        return $this->hasChild($name);
    }
}
