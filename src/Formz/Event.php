<?php

namespace Formz;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Event.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Event extends BaseEvent
{
    protected $field;
    protected $data;
    protected $input;

    /**
     * Constructor.
     *
     * @param Field $field The field object
     * @param mixed $data  The field data
     * @param mixed $input The field input
     */
    public function __construct(Field $field, $data = null, $input = null)
    {
        $this->field = $field;
        $this->data = $data;
        $this->input = $input;
    }

    /**
     * Gets the field object.
     *
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Gets the field data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data.
     *
     * @param mixed $data The form data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Sets the input data.
     *
     * @param mixed $input The input data
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * Gets the input data.
     *
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }
}
