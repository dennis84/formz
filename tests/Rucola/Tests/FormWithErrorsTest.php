<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class FormWithErrorsTest extends \PHPUnit_Framework_TestCase
{
    public function test_form_errors()
    {
        $rucola = new Rucola();

        $form = $rucola->form([
            $rucola->field('username')->nonEmptyText(),
            $rucola->field('password')->nonEmptyText(),
            $rucola->embed('address', [
                $rucola->field('city')->nonEmptyText(),
                $rucola->field('street')->nonEmptyText(),
            ]),
        ]);

        $form->bind([
            'username' => '',
            'password' => '',
            'address' => [
                'city' => '',
                'street' => '',
            ],
        ]);

        $form->fold(function ($formWithErrors) {
            $errors = $formWithErrors->getErrorsFlat();
            $this->assertEquals('username', $errors[0]->getField());
            $this->assertEquals('password', $errors[1]->getField());
            $this->assertEquals('city', $errors[2]->getField());
            $this->assertEquals('street', $errors[3]->getField());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }
}
