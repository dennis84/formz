<?php

namespace Formz\Tests\Integration;

use Formz\Builder;

class ConstraintsExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test_nonEmptyText_with_empty_string()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText(),
        ]);

        $form->bind([ 'username' => '' ]);

        $this->assertSame(
            'formz.error.non_empty_text',
            $form->getErrorsFlat()[0]->getMessage()
        );
    }

    public function test_integer_fail()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('integer')->integer()
        ]);

        $form->bind([ 'float' => '12a' ]);

        $this->assertSame(
            'formz.error.integer',
            $form->getErrorsFlat()[0]->getMessage()
        );
    }

    public function test_integer_pass()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('integer')->integer(),
            $builder->field('float')->integer(),
        ]);

        $form->bind([
            'integer' => '12',
            'float' => '42.23',
        ]);
        $formData = $form->getData();

        $this->assertSame(12, $formData['integer']);
        $this->assertSame(42, $formData['float']);
    }

    public function test_nonEmptyText_with_nothing()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('username')->nonEmptyText()
        ]);

        $form->bind([]);

        $this->assertSame(1, count($form['username']->getErrors()));
        $this->assertSame(
            'formz.error.non_empty_text',
            $form->getErrorsFlat()[0]->getMessage()
        );
    }

    public function test_number_fail()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('float')->float()
        ]);

        $form->bind([ 'float' => '12a' ]);

        $this->assertSame(
            'formz.error.float',
            $form->getErrorsFlat()[0]->getMessage()
        );
    }

    public function test_number_pass()
    {
        $builder = new Builder();

        $form = $builder->form([
            $builder->field('integer')->float(),
            $builder->field('float')->float(),
        ]);

        $form->bind([
            'integer' => '12',
            'float' => '12.23',
        ]);
        $formData = $form->getData();

        $this->assertSame(12.0, $formData['integer']);
        $this->assertSame(12.23, $formData['float']);
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
