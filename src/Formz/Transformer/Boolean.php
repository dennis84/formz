<?php

namespace Formz\Transformer;

use Formz\TransformerInterface;

/**
 * Boolean.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Boolean implements TransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        if ('false' === $data) {
            $data = false;
        }

        return (boolean) $data;
    }
}
