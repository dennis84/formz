<?php

namespace Formz\Constraint;

use Formz\Field;
use Formz\Constraint;

/**
 * NonEmptyText.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class NonEmptyText extends Constraint
{
    /**
     * {@inheritDoc}
     */
    protected function check($value)
    {
        return is_string($value) && null !== $value && '' !== $value;
    }
}
