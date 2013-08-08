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
    protected $value;
    protected $input;

    /**
     * Constructor.
     *
     * @param Field $field The field object
     * @param mixed $data  The field data
     * @param mixed $value The field value
     * @param mixed $input The field input
     */
    public function __construct(Field $field, $data, $value, $input)
    {
        $this->field = $field;
        $this->data = $data;
        $this->value = $value;
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
     * Gets the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
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
