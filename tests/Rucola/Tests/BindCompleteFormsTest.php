<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\User;
use Rucola\Tests\Model\Address;
use Rucola\Tests\Model\Location;

class BindCompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function testFormWithoutApply()
    {
        $rucola = new Rucola();

        $form = $rucola->mapping(array(
            'username' => $rucola->type('text'),
            'password' => $rucola->type('text')
        ));

        $data = array(
            'username' => 'dennis84',
            'password' => 'demo123'
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals($data, $formData);
        });
    }

    public function testFormAppliedToUser()
    {
        $rucola = new Rucola();

        $form = $rucola->mapping(array(
            'username' => $rucola->type('text'),
            'password' => $rucola->type('text')
        ), function ($username, $password) {
            return new User($username, $password);
        });

        $data = array(
            'username' => 'dennis84',
            'password' => 'demo123'
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertInstanceOf('Rucola\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('demo123', $formData->password);
        });
    }

    public function testNestedFormWithApplyToUserAndAddress()
    {
        $rucola = new Rucola();

        $form = $rucola->mapping(array(
            'username' => $rucola->type('text'),
            'password' => $rucola->type('text'),
            'address' => $rucola->mapping(array(
                'city' => $rucola->type('text'),
                'street' => $rucola->type('text')
            ), function ($city, $street) {
                return new Address($city, $street);
            }),
        ), function ($username, $password, $address) {
            return new User($username, $password, $address);
        });

        $data = array(
            'username' => 'dennis84',
            'password' => 'demo123',
            'address' => array(
                'city'   => 'Footown',
                'street' => 'Foostreet 12',
            ),
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertInstanceOf('Rucola\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('demo123', $formData->password);
            $this->assertInstanceOf('Rucola\Tests\Model\Address', $formData->address);
            $this->assertEquals('Footown', $formData->address->city);
            $this->assertEquals('Foostreet 12', $formData->address->street);
        });
    }
}
