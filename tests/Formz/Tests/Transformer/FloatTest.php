<?php

namespace Formz\Tests;

class FloatTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return array(
            array(42.2, 42.2),
            array(42.0, 42),
            array(42.2, '42.2'),
            array(42.0, '42'),

            // do not transform invalid values
            // @todo or throw an exception here?
            array('42a', '42a'),
        );
    }
    
    /**
     * @dataProvider validData
     */
    public function testTransform($expected, $value)
    {
        $transformer = new \Formz\Transformer\Float();
        $this->assertSame($expected, $transformer->transform($value));
    }
}
