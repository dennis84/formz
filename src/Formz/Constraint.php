<?php

namespace Formz;

/**
 * Constraint.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Constraint
{
    protected $message;
    protected $check;

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
        $data  = $field->getValue();

        if (!is_array($data)) {
            $data = [$data];
        }

        $result = call_user_func_array($check, $data);

        if (false === $result) {
            $field->addError(new Error($field->getFieldName(), $this->message));
        }

        return $result;
    }
}
