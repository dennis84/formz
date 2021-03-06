<?php

namespace Formz\Transformer;

use Formz\Transformer;

/**
 * Boolean.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Boolean extends Transformer
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
