<?php

namespace Formz\Transformer;

use Formz\TransformerInterface;

/**
 * BooleanTransformer.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class BooleanTransformer implements TransformerInterface
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
