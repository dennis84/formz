<?php

namespace Formz\Transformer;

use Formz\Transformer;

/**
 * Float.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Float extends Transformer
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
