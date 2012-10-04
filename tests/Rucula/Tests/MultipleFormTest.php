<?php

namespace Rucula\Tests;

use Rucula\Rucula;

class MultipleFormTest extends \PHPUnit_Framework_TestCase
{
    public function testPass()
    {
        $rucula = new Rucula();
        $form = $rucula->mapping(array(
            'choices' => $rucula->multiple($rucula->type('text')),
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
        $rucula = new Rucula();
        $form = $rucula->mapping(array(
            'choices' => $rucula->multiple($rucula->type('text')),
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
        $rucula = new Rucula();
        $form = $rucula->mapping(array(
            'choices' => $rucula->multiple($rucula->mapping(array(
                'key'   => $rucula->type('text'),
                'value' => $rucula->type('text'),
            ))),
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
}
