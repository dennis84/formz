<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class CustomConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testVerifySingleField()
    {
        $rucula = new Rucola();

        $form = $rucula->form(array(
            $rucula->field('username')->verifying('Username taken.', function ($username) {
                return 'dennis84' !== $username;
            })
        ));

        $form->bind(array(
            'username' => 'dennis84',
        ));

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('Username taken.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }

    public function testVerifySimpleForm()
    {
        $rucula = new Rucola();

        $form = $rucula->form(array(
            $rucula->field('username'),
            $rucula->field('password'),
            $rucula->field('password2'),
        ))->verifying('Invalid password or username.', function ($username, $password, $password2) {
            return $password === $password2;
        });

        $form->bind(array(
            'username' => 'dennis84',
            'password' => 'demo123',
            'password2' => 'demo',
        ));

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('Invalid password or username.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }
}
