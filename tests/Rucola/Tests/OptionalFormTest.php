<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\Address;
use Rucola\Tests\Model\User;

class OptionalFormTest extends \PHPUnit_Framework_TestCase
{
    public function test_bind_nested_form_applied_to_object()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->optionalEmbed('address', [
                $rucola->field('city'),
                $rucola->field('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            }),
        ], function ($username, $password, Address $address = null) {
            return new User($username, $password, $address);
        });

        $data = [
            'username' => 'dennis84',
            'password' => 'demo123',
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
        }, function ($formData) {
            $this->assertInstanceOf('Rucola\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('demo123', $formData->password);

            $this->assertEquals(null, $formData->address);
        });
    }
}
