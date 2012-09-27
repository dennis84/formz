<?php

namespace Rucula\Tests;

use Rucula\Rucula;
use Rucula\Tests\Model\User;
use Rucula\Tests\Model\Address;
use Rucula\Tests\Model\Location;

class BindIncompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function testFormWithoutApply()
    {
        $rucula = new Rucula();

        $form = $rucula->mapping(array(
            'username' => $rucula['type.text'],
            'password' => $rucula['type.text']
        ));

        $data = array(
            'username' => 'dennis84',
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals(array(
                'username' => 'dennis84',
                'password' => '',
            ), $formData);
        });
    }

    public function testFormAppliedToUser()
    {
        $rucula = new Rucula();

        $form = $rucula->mapping(array(
            'username' => $rucula['type.text'],
            'password' => $rucula['type.text']
        ), function ($username, $password) {
            return new User($username, $password);
        });

        $data = array(
            'username' => 'dennis84',
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertInstanceOf('Rucula\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('', $formData->password);
        });
    }

    /**
     * It should not be possible to bind incomplete data to a nested form.
     * The value could not be mapped to the closure so throw an exception.
     *
     * @expectedException InvalidArgumentException
     */
    public function testNestedFormAppliedToUserAndAddress()
    {
        $rucula = new Rucula();

        $form = $rucula->mapping(array(
            'username' => $rucula['type.text'],
            'password' => $rucula['type.text'],
            'address' => $rucula->mapping(array(
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
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
        }, function ($formData) {
        });
    }

    public function testNestedOptionalFormAppliedToUserAndAddress()
    {
        $rucula = new Rucula();

        $form = $rucula->mapping(array(
            'username' => $rucula['type.text'],
            'password' => $rucula['type.text'],
            'address' => $rucula->optional($rucula->mapping(array(
                'city' => $rucula['type.text'],
                'street' => $rucula['type.text']
            ), function ($city, $street) {
                return new Address($city, $street);
            })),
        ), function ($username, $password, $address) {
            return new User($username, $password, $address);
        });

        $data = array(
            'username' => 'dennis84',
            'password' => 'demo123',
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
        }, function ($formData) {
            $this->assertInstanceOf('Rucula\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('demo123', $formData->password);

            $this->assertEquals(null, $formData->address);
        });
    }
}
