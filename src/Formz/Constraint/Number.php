<?php

namespace Formz\Constraint;

use Formz\Field;
use Formz\Constraint;

/**
 * Number.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Number extends Constraint
{
    /**
     * {@inheritDoc}
     */
    protected function check($value)
    {
        return is_numeric($value);
    }
}
