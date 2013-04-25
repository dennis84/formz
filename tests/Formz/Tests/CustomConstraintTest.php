<?php

namespace Formz\Tests;

use Formz\Builder;

class CustomConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function test_verify_single_field()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->verifying('Username taken.', function ($username) {
                return 'dennis84' !== $username;
            })
        ]);

        $form->bind([
            'username' => 'dennis84',
        ]);

        $this->assertEquals('Username taken.', $form->getErrorsFlat()[0]->getMessage());
    }

    public function test_verify_simple_form()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username'),
            $builder->field('password'),
            $builder->field('password2'),
        ])->verifying('Invalid password or username.', function ($username, $password, $password2) {
            return $password === $password2;
        })->verifying('Username taken.', function ($username, $password, $password2) {
            return 'dennis84' !== $username;
        });

        $form->bind([
            'username' => 'dennis84',
            'password' => 'demo123',
            'password2' => 'demo',
        ]);

        $this->assertEquals('Invalid password or username.', $form->getErrorsFlat()[0]->getMessage());
        $this->assertEquals('Username taken.', $form->getErrorsFlat()[1]->getMessage());
    }
}
