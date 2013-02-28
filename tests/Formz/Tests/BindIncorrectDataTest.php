<?php

namespace Formz\Tests;

use Formz\Builder;
use Formz\Tests\Model\User;
use Formz\Tests\Model\Address;
use Formz\Tests\Model\Location;

class BindIncorrectDataTest extends \PHPUnit_Framework_TestCase
{
    public function test_flat_form_applied_to_array()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
        ]);

        $data = [
            'foo' => [
                'username' => 'dennis84',
                'password' => 'demo123',
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals('', $formData['username']);
            $this->assertEquals('', $formData['password']);
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
            'foo' => [
                'username' => 'dennis84',
                'password' => 'demo123',
            ],
        ];

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertSame(null, $formData->username);
            $this->assertSame(null, $formData->password);
        });
    }
}
