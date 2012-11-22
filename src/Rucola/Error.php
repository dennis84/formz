<?php

namespace Rucola;

/**
 * Error.
 */
class Error
{
    protected $type;
    protected $message;

    /**
     * Constructor.
     *
     * @param string $type    The error type
     * @param string $message The error message
     */
    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Gets the type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
