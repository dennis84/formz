<?php

namespace Rucola\Tests\Model;

class Address
{
    public $city;
    public $street;
    public $location;

    public function __construct($city, $street, $location = null)
    {
        $this->city     = $city;
        $this->street   = $street;
        $this->location = $location;
    }
}