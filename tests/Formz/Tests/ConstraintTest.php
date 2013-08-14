<?php

namespace Formz\Tests;

class ConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $constraint = new \Formz\Constraint\Number('');
        $result = $constraint->validate('42');
        $this->assertTrue($result);
    }

    public function testValidateManyTimes()
    {
        $constraint = new \Formz\Constraint\Number('');

        $result = $constraint->validate('42');
        $this->assertTrue($result);

        // The result is always the same.
        $secondResult = $constraint->validate('foo');
        $this->assertSame($result, $secondResult);
    }
}
