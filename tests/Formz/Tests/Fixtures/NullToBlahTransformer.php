<?php

namespace Formz\Tests\Fixtures;

use Formz\Transformer;

class NullToBlahTransformer extends Transformer
{
    public function transform($data)
    {
        return null === $data ? 'blah' : $data;
    }
}
