<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class ConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testNonEmptyText()
    {
        $rucula = new Rucola();

        $form = $rucula->form('form', array(
            $rucula->field('username')->nonEmptyText()
        ));

        $form->bind(array(
            'username' => '',
        ));

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('This field must not be empty.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }
}
