<?php

namespace Formz\Transformer;

use Formz\TransformerInterface;

/**
 * NumberTransformer.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class NumberTransformer implements TransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        return floatval($data);
    }
}
