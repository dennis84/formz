<?php

namespace Rucola\Util;

use Rucola\Field;

/**
 * RecursiveFieldIterator.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class RecursiveFieldIterator extends \ArrayIterator implements \RecursiveIterator
{
    /**
     * Construct.
     *
     * @param Field $field The form field
     */
    function __construct(Field $field)
    {
        parent::__construct($field->getChildren());
    }

    /**
     * {@inheritdoc}
     */
    function hasChildren()
    {
        return $this->current()->hasChildren();
    }

    /**
     * {@inheritdoc}
     */
    function getChildren()
    {
        return new self($this->current());
    }
}
