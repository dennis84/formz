<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class FormWithErrorsTest extends \PHPUnit_Framework_TestCase
{
    public function testFormErrors()
    {
        $rucula = new Rucola();

        $form = $rucula->form('form', array(
            $rucula->field('username')->nonEmptyText(),
            $rucula->field('password')->nonEmptyText(),
        ));

        $form->bind(array(
            'username' => '',
            'password' => '',
        ));

        $form->fold(function ($formWithErrors) {
            $errors = $formWithErrors->getErrorsFlat();
            $this->assertEquals('username', $errors[0]->getField());
            $this->assertEquals('password', $errors[1]->getField());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }
}
