<?php

namespace Rucola;

/**
 * Some default constaint extensions for the field object.
 */
trait Constraints
{
    /**
     * Checks if the field value is not empty.
     *
     * @param string $message The error message
     *
     * @return Field
     */
    public function nonEmptyText($message = 'This field must not be empty.')
    {
        $this->addConstraint(new Constraint($message, function ($value) {
            return '' !== $value;
        }));

        return $this;
    }
}
