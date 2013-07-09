<?php

namespace Formz\Transformer;

use Formz\TransformerInterface;

/**
 * Integer.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Integer implements TransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        return intval($data);
    }
}
