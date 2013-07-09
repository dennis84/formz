<?php

namespace Formz\Transformer;

use Formz\TransformerInterface;

/**
 * Float.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Float implements TransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        return floatval($data);
    }
}
