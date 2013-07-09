<?php

namespace Formz\Tests\Constraint;

class NonEmptyTextTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return array(
            array(true, 'foo'),
            array(true, '\\n'),
            array(true, ' '),
            array(false, ''),
            array(false, 1),
            array(false, array()),
        );
    }

    /**
     * @dataProvider validData
     */
    public function testCheck($expected, $value)
    {
        $constraint = new \Formz\Constraint\NonEmptyText('');
        $this->assertSame($expected, $constraint->check($value));
    }
}
