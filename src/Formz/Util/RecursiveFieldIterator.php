<?php

namespace Formz\Util;

use Formz\Field;

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
    public function __construct(Field $field)
    {
        parent::__construct($field->getChildren());
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        return $this->current()->hasChildren();
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return new self($this->current());
    }
}
