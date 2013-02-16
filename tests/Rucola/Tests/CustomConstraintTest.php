<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class CustomConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function test_verify_single_field()
    {
        $rucula = new Rucola();

        $form = $rucula->form([
            $rucula->field('username')->verifying('Username taken.', function ($username) {
                return 'dennis84' !== $username;
            })
        ]);

        $form->bind([
            'username' => 'dennis84',
        ]);

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('Username taken.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }

    public function test_verify_simple_form()
    {
        $rucula = new Rucola();

        $form = $rucula->form([
            $rucula->field('username'),
            $rucula->field('password'),
            $rucula->field('password2'),
        ])->verifying('Invalid password or username.', function ($username, $password, $password2) {
            return $password === $password2;
        });

        $form->bind([
            'username' => 'dennis84',
            'password' => 'demo123',
            'password2' => 'demo',
        ]);

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('Invalid password or username.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }
}
