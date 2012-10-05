<?php

namespace Rucola\Tests\Type;

use Rucola\Rucola;

class NonEmptyTextTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testPass()
    {
        $rucola = new Rucola();
        $form = $rucola->mapping(array(
            'username' => $rucola->type('non_empty_text'),
            'password' => $rucola->type('non_empty_text'),
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
        $rucola = new Rucola();
        $form = $rucola->mapping(array(
            'username' => $rucola->type('non_empty_text'),
            'password' => $rucola->type('non_empty_text'),
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
