<?php

namespace Rucula\Tests;

use Rucula\Rucula;
use Rucula\Tests\Model\User;
use Rucula\Tests\Model\Address;
use Rucula\Tests\Model\Location;

class BindCompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function testFormWithoutApply()
    {
        $rucula = new Rucula();

        $form = $rucula['builder.tuple']->build(array(
            'username' => $rucula['type.text'],
            'password' => $rucula['type.text']
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
        $rucula = new Rucula();

        $form = $rucula['builder.tuple']->build(array(
            'username' => $rucula['type.text'],
            'password' => $rucula['type.text']
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
            $this->assertInstanceOf('Rucula\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('demo123', $formData->password);
        });
    }

    public function testNestedFormWithApplyToUserAndAddress()
    {
        $rucula = new Rucula();

        $form = $rucula['builder.tuple']->build(array(
            'username' => $rucula['type.text'],
            'password' => $rucula['type.text'],
            'address' => $rucula['builder.tuple']->build(array(
                'city' => $rucula['type.text'],
                'street' => $rucula['type.text']
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
            )
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertInstanceOf('Rucula\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('demo123', $formData->password);
            $this->assertInstanceOf('Rucula\Tests\Model\Address', $formData->address);
            $this->assertEquals('Footown', $formData->address->city);
            $this->assertEquals('Foostreet 12', $formData->address->street);
        });
    }
}
