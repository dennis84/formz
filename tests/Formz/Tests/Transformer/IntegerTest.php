<?php

namespace Formz\Tests;

class IntegerTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return array(
            array(42, 42.2),
            array(42, 42),
            array(42, '42.2'),
            array(42, '42'),
        );
    }
    
    /**
     * @dataProvider validData
     */
    public function testTransform($expected, $value)
    {
        $transformer = new \Formz\Transformer\Integer();
        $this->assertSame($expected, $transformer->transform($value));
    }
}
