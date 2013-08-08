<?php

namespace Formz;

/**
 * Constraint.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
abstract class Constraint
{
    protected $message;
    protected $checked = false;
    protected $result = false;

    /**
     * Constructor.
     *
     * @param string $message The error message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Checks is the field is valid.
     *
     * @param mixed $value The field value
     *
     * @return boolean
     */
    abstract protected function check($value);

    /**
     * Validates the data against this constraint. If the constraint was already
     * triggered before, then it will return the last result.
     *
     * @param mixed $data The data
     *
     * @return boolean
     */
    public function validate($data)
    {
        if (true === $this->checked) {
            return $this->result;
        }

        $this->checked = true;
        $this->result = $this->check($data);
        return $this->result;
    }

    /**
     * Gets the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
