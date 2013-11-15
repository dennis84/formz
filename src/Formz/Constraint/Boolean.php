<?php

namespace Formz\Constraint;

use Formz\Constraint;

/**
 * Boolean.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Boolean extends Constraint
{
    /**
     * {@inheritDoc}
     */
    protected function check($value)
    {
        return 'true' === $value
            || 'false' === $value
            || true === $value
            || false === $value;
    }
}
