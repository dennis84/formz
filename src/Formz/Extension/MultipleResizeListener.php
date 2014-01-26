<?php

namespace Formz\Extension;

use Formz\Event;
use Formz\Field;

/**
 * MultipleResizeListener.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class MultipleResizeListener
{
    /**
     * On bind.
     *
     * @param Event $event The form event
     */
    public function bind(Event $event)
    {
        if (null === $event->getInput()) {
            $event->setInput([]);
        }

        $this->prepare($event->getField(), $event->getInput());
    }

    /**
     * On fill.
     *
     * @param Event $event The form event
     */
    public function fill(Event $event)
    {
        $this->prepare($event->getField(), $event->getData());
    }

    /**
     * Prepares the multiple field.
     *
     * @param Field $field The field object
     * @param mixed $data  The data
     */
    protected function prepare(Field $field, $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('');
        }

        $choices = [];
        foreach ($data as $index => $value) {
            $proto  = $field->getOption('prototype');
            $choice = $this->cloneField($proto);

            $choice->setInternalName((string) $index);
            $choice->setParent($field);

            foreach ($choice->getChildren() as $child) {
                $child->setParent($choice);
            }

            $choices[] = $choice;
        }

        $field->setChildren($choices);
    }

    /**
     * Clones a field.
     *
     * @param Field $field
     *
     * @return Field The cloned field
     */
    protected function cloneField(Field $field)
    {
        $clone = clone $field;

        $clone->setChildren(array_map(function ($child) {
            return $this->cloneField($child);
        }, $field->getChildren()));

        $clone->setConstraints(array_map(function ($constraint) {
            return clone $constraint;
        }, $field->getConstraints()));

        return $clone;
    }
}
