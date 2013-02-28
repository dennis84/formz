<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Tests\Model\User;
use Formz\Tests\Model\Address;
use Formz\Tests\Model\Location;

class BindIncompleteFormsTest extends \PHPUnit_Framework_TestCase
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
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertInstanceOf('Formz\Tests\Model\User', $formData);
            $this->assertEquals('dennis84', $formData->username);
            $this->assertEquals('', $formData->password);
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
                $builder->field('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            })->required(),
        ], function ($username, $password, Address $address) {
            return new User($username, $password, $address);
        });

        $data = [
            'username' => 'dennis84',
            'password' => 'demo123',
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->assertSame(true, true);
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }
}
