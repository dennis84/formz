<?php

namespace Formz\Tests\Constraint;

class NonEmptyTextTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [true, 'foo'],
            [true, '\\n'],
            [true, ' '],
            [false, ''],
            [false, 1],
            [false, []],
        ];
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
