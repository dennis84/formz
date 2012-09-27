<?php

namespace Rucula\Tests\Type;

use Rucula\Rucula;

class NonEmptyTextTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testPass()
    {
        $rucula = new Rucula();
        $form = $rucula->mapping(array(
            'username' => $rucula['type.non_empty_text'],
            'password' => $rucula['type.non_empty_text'],
        ));

        $data = array(
            'username' => 'foo',
            'password' => 'bar'
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals($formData, $data);
        });
    }

    public function testFail()
    {
        $rucula = new Rucula();
        $form = $rucula->mapping(array(
            'username' => $rucula['type.non_empty_text'],
            'password' => $rucula['type.non_empty_text'],
        ));

        $form->bind(array(
            'username' => '',
            'password' => ''
        ));

        $form->fold(function ($formWithErrors) {
            $errors = $formWithErrors->getErrorsFlat();
            $this->assertEquals(2, count($errors));
        }, function ($formData) {
            $this->fail('The form must be valid here.');
        });
    }
}
