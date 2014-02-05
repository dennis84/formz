<?php

namespace Formz\Tests;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return array(
            array(true, true),
            array(true, 'true'),
            array(false, false),
            array(false, 'false'),
        );
    }

    /**
     * @dataProvider validData
     */
    public function testTransform($expected, $value)
    {
        $transformer = new \Formz\Transformer\Boolean();
        $this->assertSame($expected, $transformer->transform($value));
    }
}
