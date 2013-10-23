<?php

namespace Formz\Tests\Fixtures;

use Formz\Transformer;

class NonsenseTransformer extends Transformer
{
    public function transform($data)
    {
        return [ 'foo' => $data['foo'] * 10 ];
    }
}
