<?php

namespace Formz\Transformer;

class BooleanTransformer
{
    public function transform($data)
    {
        if ('false' === $data) {
            $data = false;
        }

        return (boolean) $data;
    }
}
