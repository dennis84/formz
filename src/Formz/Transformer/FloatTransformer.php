<?php

namespace Formz\Transformer;

use Formz\TransformerInterface;

/**
 * FloatTransformer.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class FloatTransformer implements TransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        return floatval($data);
    }
}
