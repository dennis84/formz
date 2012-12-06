<?php

namespace Rucola;

/**
 * Error.
 */
class Error
{
    protected $field;
    protected $message;

    /**
     * Constructor.
     *
     * @param string $field   The field name
     * @param string $message The error message
     */
    public function __construct($field, $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * Gets field name.
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
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
