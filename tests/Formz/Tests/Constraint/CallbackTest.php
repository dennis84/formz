<?php

namespace Formz\Tests\Constraint;

use Formz\Constraint\Callback;

class CallbackTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck()
    {
        $expected = 'foo';
        $constraint = new Callback('', function ($value) use ($expected) {
            $this->assertSame($expected, $value);
            return true;
        });

        $this->assertTrue($constraint->check($expected));
    }
}
