<?php

namespace Formz\Tests\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    /**
     * @Assert\Length(min=5)
     * @Assert\NotBlank
     */
    public $username;

    /**
     * @Assert\Length(min=5)
     * @Assert\NotBlank
     */
    public $password;

    /**
     * @Assert\NotBlank
     */
    public $firstName;

    /**
     * @Assert\NotBlank
     */
    public $last_name;

    public $address;

    public function __construct($username, $password, $address  = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->address  = $address;
    }

    /**
     * @Assert\True
     */
    public function isPasswordValid()
    {
        return false;
    }
}
