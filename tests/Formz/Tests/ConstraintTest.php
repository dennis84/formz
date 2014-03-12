<?php

namespace Formz\Tests;

class ConstraintTest extends FormzTestCase
{
    public function testValidate()
    {
        $field = $this->createField('foo');
        $constraint = new \Formz\Constraint\Number('');
        $constraint->validate($field, 42);
        $this->assertCount(0, $field->getErrors());
    }

    public function testValidateFail()
    {
        $field = $this->createField('foo');
        $constraint = new \Formz\Constraint\Number('');
        $constraint->validate($field, 'foo');
        $this->assertCount(1, $field->getErrors());
    }

    public function testValidateManyTimes()
    {
        $field = $this->createField('foo');
        $constraint = new \Formz\Constraint\Number('');

        $constraint->validate($field, 'foo');
        $this->assertCount(1, $field->getErrors());

        $constraint->validate($field, 'foo');
        $this->assertCount(1, $field->getErrors());
    }
}
