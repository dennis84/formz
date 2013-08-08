<?php

namespace Formz\Constraint;

use Formz\Field;
use Formz\Constraint;

/**
 * Required.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Required extends Constraint
{
    /**
     * {@inheritDoc}
     */
    protected function check($value)
    {
        return null !== $value && '' !== $value;
    }
}
