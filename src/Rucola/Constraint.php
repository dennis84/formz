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
     *
     * @return boolean
     */
    public function check(Field $field)
    {
        $check = $this->check;
        $data  = $field->getData();

        if (!is_array($data)) {
            $data = array($data);
        }

        $result = call_user_func_array($check, $data);

        if (false === $result) {
            $field->addError(new Error($field->getFieldName(), $this->message));
        }

        return $result;
    }
}
