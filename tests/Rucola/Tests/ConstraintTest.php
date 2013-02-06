<?php

namespace Rucola\Tests;

use Rucola\Rucola;

class ConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testNonEmptyText()
    {
        $rucula = new Rucola();

        $form = $rucula->form([
            $rucula->field('username')->nonEmptyText()
        ]);

        $form->bind([
            'username' => '',
        ]);

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('This field must not be empty.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }

    public function testNonEmptyTextBoundWithNothing()
    {
        $rucula = new Rucola();

        $form = $rucula->form([
            $rucula->field('username')->nonEmptyText()
        ]);

        $form->bind([]);

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('This field must not be empty.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }

    public function testNumberFails()
    {
        $rucula = new Rucola();

        $form = $rucula->form([
            $rucula->field('number')->number()
        ]);

        $form->bind([
            'number' => '12a',
        ]);

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('This field must contain numeric values.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }

    public function testNumberPass()
    {
        $rucula = new Rucola();

        $form = $rucula->form([
            $rucula->field('integer')->number(),
            $rucula->field('float')->number(),
        ]);

        $form->bind([
            'integer' => '12',
            'float' => '12.23',
        ]);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertEquals(12, $formData['integer']);
            $this->assertEquals(12.23, $formData['float']);
        });
    }

    public function testBoolean()
    {
        $rucula = new Rucola();

        $form = $rucula->form([
            $rucula->field('a')->boolean(),
            $rucula->field('b')->boolean(),
            $rucula->field('c')->boolean(),
            $rucula->field('d')->boolean(),
        ]);

        $form->bind([
            'a' => true,
            'b' => false,
            'c' => 'true',
            'd' => 'false',
        ]);

        $form->fold(function ($formWithErrors) {
            $this->fail('The form must be valid here.');
        }, function ($formData) {
            $this->assertTrue($formData['a']);
            $this->assertFalse($formData['b']);
            $this->assertTrue($formData['c']);
            $this->assertFalse($formData['d']);
        });
    }
}
