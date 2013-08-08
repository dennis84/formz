<?php

namespace Formz\Tests\Constraint;

class RequiredTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return array(
            array(true, 'foo'),
            array(true, 1),
            array(true, 0),
            array(true, array()),
            array(false, ''),
            array(false, null),
        );
    }

    /**
     * @dataProvider validData
     */
    public function testCheck($expected, $value)
    {
        $constraint = new \Formz\Constraint\Required('');
        $this->assertSame($expected, $constraint->validate($value));
    }
}
