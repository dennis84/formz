<?php

namespace Rucula\Tests\Type;

use Rucula\Rucula;

class BooleanTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testPass()
    {
        $rucula = new Rucula();
        $form = $rucula->mapping(array(
            'accept' => $rucula['type.boolean'],
            'done'   => $rucula['type.boolean'],
        ));

        $form->bind(array(
            'accept' => 'true',
            'done'   => 'false',
        ));

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, array(
                'accept' => true,
                'done' => false,
            ));
        });
    }

    public function testFail()
    {
        $rucula = new Rucula();
        $form = $rucula->mapping(array(
            'accept'  => $rucula['type.boolean'],
            'done'    => $rucula['type.boolean'],
            'checked' => $rucula['type.boolean'],
            'foo'     => $rucula['type.boolean'],
        ));

        $form->bind(array(
            'accept'  => '1',
            'done'    => '0',
            'checked' => 'on',
            'foo'     => 'bar',
        ));

        $form->fold(function ($formWithErrors) {
            $errors = $formWithErrors->getErrorsFlat();
            $this->assertEquals(4, count($errors));
        }, function ($formData) {
            $this->fail('The form must be valid here.');
        });
    }
}
