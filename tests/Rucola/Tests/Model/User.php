<?php

namespace Rucola\Tests\Model;

class User
{
    public $username;
    public $password;
    public $address;

    public function __construct($username, $password, $address  = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->address  = $address;
    }
}

