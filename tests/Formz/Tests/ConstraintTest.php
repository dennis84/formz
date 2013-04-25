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

        $this->assertEquals('This field must not be empty.', $form->getErrorsFlat()[0]->getMessage());
    }

    public function test_nonEmptyText_with_nothing()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText()
        ]);

        $form->bind([]);

        $this->assertSame(1, count($form['username']->getErrors()));
        $this->assertEquals('This field must not be empty.', $form->getErrorsFlat()[0]->getMessage());
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

        $this->assertEquals('This field must contain numeric values.', $form->getErrorsFlat()[0]->getMessage());
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
        $formData = $form->getData();

        $this->assertEquals(12, $formData['integer']);
        $this->assertEquals(12.23, $formData['float']);
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
        $formData = $form->getData();

        $this->assertTrue($formData['a']);
        $this->assertFalse($formData['b']);
        $this->assertTrue($formData['c']);
        $this->assertFalse($formData['d']);
    }
}
