<?php

namespace Formz\Integration\Tests;

use Formz\Builder;
use Formz\Tests\Fixtures\User;
use Formz\Tests\Fixtures\Address;

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
        $formData = $form->getData();

        $this->assertSame([
            'username' => 'dennis84',
            'password' => null,
        ], $formData);
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
        $formData = $form->getData();

        $this->assertInstanceOf('Formz\Tests\Fixtures\User', $formData);
        $this->assertSame('dennis84', $formData->username);
        $this->assertSame(null, $formData->password);
    }

    public function test_nested_form_applied_to_object()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('address', [
                $builder->field('city'),
                $builder->field('street'),
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

        $this->assertFalse($form->isValid());
    }
}
