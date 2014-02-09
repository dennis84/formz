<?php

namespace Formz\Tests\Integration;

use Formz\Builder;
use Formz\Tests\Fixtures\User;

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
        $formData = $form->getData();

        $this->assertSame(null, $formData['username']);
        $this->assertSame(null, $formData['password']);
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
        $formData = $form->getData();

        $this->assertSame(null, $formData->username);
        $this->assertSame(null, $formData->password);
    }
}
