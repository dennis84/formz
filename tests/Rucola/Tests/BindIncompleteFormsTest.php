<?php

namespace Rucola\Tests;

use Rucola\Rucola;
use Rucola\Tests\Model\User;
use Rucola\Tests\Model\Address;
use Rucola\Tests\Model\Location;

class BindIncompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function testFlatFormWithoutApply()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
        ]);

        $data = [
            'username' => 'dennis84',
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals([
                'username' => 'dennis84',
                'password' => '',
            ], $formData);
        });
    }

    public function testFlatFormAppliedToUser()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $data = [
            'username' => 'dennis84',
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertInstanceOf('Rucola\Tests\Model\User', $formData);
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
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username'),
            $rucola->field('password'),
            $rucola->embed('address', [
                $rucola->field('city'),
                $rucola->field('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            }),
        ], function ($username, $password, $address) {
            return new User($username, $password, $address);
        });

        $data = [
            'username' => 'dennis84',
            'password' => 'demo123',
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
        }, function ($formData) {
        });
    }

    public function testNestedOptionalFormAppliedToUserAndAddress()
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
        ], function ($username, $password, $address) {
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
