<?php

namespace Formz\Transformer;

use Formz\Transformer;

/**
 * Integer.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Integer extends Transformer
{
    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        if (!is_numeric($data)) {
            return $data;
        }

        return intval($data);
    }
}
