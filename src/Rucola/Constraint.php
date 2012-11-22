<?php

namespace Rucola;

/**
 * Constraint.
 */
class Constraint
{
    /**
     * Constructor.
     *
     * @param string  $message The error message
     * @param Closure $check   The check function
     */
    public function __construct($message, \Closure $check)
    {
        $this->message = $message;
        $this->check = $check;
    }

    /**
     * Applies the check function with passed field.
     *
     * @param Field $field The field object
     */
    public function check(Field $field)
    {
        $check = $this->check;
        $value = $field->getValue();

        if (!is_array($value)) {
            $value = array($value);
        }

        $result = call_user_func_array($check, $value);

        if (false === $result) {
            $field->addError(new Error('error', $this->message));
        }
    }
}
