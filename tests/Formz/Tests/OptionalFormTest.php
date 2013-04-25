<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Tests\Model\Address;
use Formz\Tests\Model\User;

class OptionalFormTest extends \PHPUnit_Framework_TestCase
{
    public function test_bind_nested_form_applied_to_object()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->optionalEmbed('address', [
                $builder->field('city'),
                $builder->field('street')
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
        $formData = $form->getData();

        $this->assertInstanceOf('Formz\Tests\Model\User', $formData);
        $this->assertEquals('dennis84', $formData->username);
        $this->assertEquals('demo123', $formData->password);

        $this->assertEquals(null, $formData->address);
    }
}
