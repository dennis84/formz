<?php

namespace Formz\Transformer;

class NumberTransformer
{
    public function transform($data)
    {
        return floatval($data);
    }
}
