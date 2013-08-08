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
        if (!is_numeric($data)) {
            return $data;
        }

        return floatval($data);
    }
}
