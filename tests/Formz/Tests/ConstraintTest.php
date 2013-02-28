<?php

namespace Formz\Tests;

use Formz\Builder;

class ConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function test_nonEmptyText_with_empty_string()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText()
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

    public function test_nonEmptyText_with_nothing()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText()
        ]);

        $form->bind([]);

        $form->fold(function ($formWithErrors) {
            $this->assertEquals('This field must not be empty.', $formWithErrors->getErrorsFlat()[0]->getMessage());
        }, function ($formData) {
            $this->fail('The form must be invalid here.');
        });
    }

    public function test_number_fail()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('number')->number()
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

    public function test_number_pass()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('integer')->number(),
            $builder->field('float')->number(),
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

    public function test_boolean()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('a')->boolean(),
            $builder->field('b')->boolean(),
            $builder->field('c')->boolean(),
            $builder->field('d')->boolean(),
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
