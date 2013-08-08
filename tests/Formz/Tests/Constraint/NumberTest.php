<?php

namespace Formz\Tests\Constraint;

class NumberTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return array(
            array(true, 1),
            array(true, 1.2),
            array(true, '1'),
            array(true, '1.2'),
            array(false, 'foo'),
        );
    }

    /**
     * @dataProvider validData
     */
    public function testCheck($expected, $value)
    {
        $constraint = new \Formz\Constraint\Number('');
        $this->assertSame($expected, $constraint->validate($value));
    }
}
