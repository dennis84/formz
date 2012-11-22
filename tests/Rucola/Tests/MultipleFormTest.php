<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class MultipleFormTest extends \PHPUnit_Framework_TestCase
{
    public function testPass()
    {
        $rucola = new Rucola();
        $form = $rucola->form('form', array(
            $rucola->field('choices')->multiple(),
        ));

        $form->bind(array(
            'choices' => array('foo', 'bar', 'baz'),
        ));

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, array(
                'choices' => array('foo', 'bar', 'baz'),
            ));
        });
    }

    public function testPassEmpty()
    {
        $rucola = new Rucola();
        $form = $rucola->form('form', array(
            $rucola->field('choices')->multiple(),
        ));

        $form->bind(array(
            'choices' => array(),
        ));

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals($formData, array(
                'choices' => array(),
            ));
        });
    }

    public function testPassNested()
    {
        $rucola = new Rucola();
        $form = $rucola->form('form', array(
            $rucola->form('choices', array(
                $rucola->field('key'),
                $rucola->field('value'),
            ))->multiple(),
        ));

        $data = array(
            'choices' => array(
                array(
                    'key' => 'foo',
                    'value' => 'bar'
                ),
                array(
                    'key' => 'bla',
                    'value' => 'blubb'
                ),
            ),
        );

        $form->bind($data);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) use ($data) {
            $this->assertEquals($formData, $data);
        });
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonArrayValueToMultipleType()
    {
        $rucola = new Rucola();
        $form = $rucola->form('form', array(
            $rucola->field('choices')->multiple(),
        ));

        $form->bind(array(
            'choices' => 'foo',
        ));
    }
}
