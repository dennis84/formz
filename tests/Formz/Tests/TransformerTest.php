<?php

namespace Formz;

use Formz\Transformer;

class TransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new NullTransformer();
        $this->assertSame('foo', $transformer->transform('foo'));
    }

    public function testReverseTransform()
    {
        $transformer = new NullTransformer();
        $this->assertSame('foo', $transformer->reverseTransform('foo'));
    }
}

class NullTransformer extends Transformer
{
}