<?php

namespace Formz\Transformer;

use Formz\TransformerInterface;

/**
 * IntegerTransformer.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class IntegerTransformer implements TransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        return intval($data);
    }
}
