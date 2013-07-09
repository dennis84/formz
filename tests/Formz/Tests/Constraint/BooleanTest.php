<?php

namespace Formz\Tests\Constraint;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return array(
            array(true, true),
            array(true, 'true'),
            array(true, false),
            array(true, 'false'),
            array(false, 1),
            array(false, '1'),
            array(false, '0'),
            array(false, 'a'),
        );
    }

    /**
     * @dataProvider validData
     */
    public function testCheck($expected, $value)
    {
        $constraint = new \Formz\Constraint\Boolean('');
        $this->assertSame($expected, $constraint->check($value));
    }
}
