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

    /**
     * Checks if the field value is numeric.
     *
     * @param string message The error message
     *
     * @return Field
     */
    public function number($message = 'This field must contain numeric values.')
    {
        $this->addConstraint(new Constraint($message, function ($value) {
            return is_numeric($value);
        }));

        $this->on('bind_value', function ($value) {
            return (float) $value;
        });

        return $this;
    }

    /**
     * Checks if the field value is boolean.
     *
     * @param string message The error message
     *
     * @return Field
     */
    public function boolean($message = 'This field must contain a boolean value')
    {
        $this->addConstraint(new Constraint($message, function ($value) {
            return 'true' === $value || 'false' === $value || true === $value || false === $value;
        }));

        $this->on('bind_value', function ($value) {
            if ('false' === $value) {
                $value = false;
            }

            return (boolean) $value;
        });

        return $this;
    }
}
