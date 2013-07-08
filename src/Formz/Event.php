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

    /**
     * Constructor.
     *
     * @param Field $field The field object
     * @param mixed $data  The field data
     */
    public function __construct(Field $field, $data)
    {
        $this->field = $field;
        $this->data = $data;
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
}
