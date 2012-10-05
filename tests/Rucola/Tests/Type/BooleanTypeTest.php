<?php

namespace Rucola\Tests\Type;

use Rucola\Rucola;

class BooleanTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testPass()
    {
        $rucola = new Rucola();
        $form = $rucola->mapping(array(
            'accept' => $rucola->type('boolean'),
            'done'   => $rucola->type('boolean'),
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
        $rucola = new Rucola();
        $form = $rucola->mapping(array(
            'accept'  => $rucola->type('boolean'),
            'done'    => $rucola->type('boolean'),
            'checked' => $rucola->type('boolean'),
            'foo'     => $rucola->type('boolean'),
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
