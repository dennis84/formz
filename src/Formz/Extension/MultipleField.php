<?php

namespace Formz\Extension;

use Formz\Field;

/**
 * Multiple extension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class MultipleField extends Field
{
    protected $prototype;

    /**
     * Sets the prototype field.
     *
     * @param Field $field The prototype field
     */
    public function setPrototype(Field $field)
    {
        $this->prototype = $field;
    }

    /**
     * Gets the prototype field.
     *
     * @return Field
     */
    public function getPrototype()
    {
        return $this->prototype;
    }
}
