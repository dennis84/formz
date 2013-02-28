<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Tests\Model\User;
use Formz\Tests\Model\Address;
use Formz\Tests\Model\Location;

class BindCompleteFormsTest extends \PHPUnit_Framework_TestCase
{
    public function test_flat_form_applied_to_array()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ]);

        $data = [
            'username' => 'dennis84',
            'password' => 'demo123'
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals($data, $formData);
        });
    }

    public function test_flat_form_applied_to_object()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $data = [
            'username' => 'dennis84',
            'password' => 'demo123'
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertInstanceOf('Formz\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('demo123', $formData->password);
        });
    }

    public function test_nested_form_applied_to_object()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->embed('address', [
                $builder->field('city'),
                $builder->field('street'),
            ], function ($city, $street) {
                return new Address($city, $street);
            }),
        ], function ($username, $password, Address $address) {
            return new User($username, $password, $address);
        });

        $data = [
            'username' => 'dennis84',
            'password' => 'demo123',
            'address' => [
                'city'   => 'Footown',
                'street' => 'Foostreet 12',
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertInstanceOf('Formz\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('demo123', $formData->password);
            $this->assertInstanceOf('Formz\Tests\Model\Address', $formData->address);
            $this->assertEquals('Footown', $formData->address->city);
            $this->assertEquals('Foostreet 12', $formData->address->street);
        });
    }
}
