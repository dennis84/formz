<?php

namespace Formz\Extension;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Formz\Event;
use Formz\Events;
use Formz\Field;

/**
 * MultipleResizeListener.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class MultipleResizeListener
{
    /**
     * On bind field.
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
     * On fill field.
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
     * @param MultipleField $field The field object
     * @param mixed         $data  The data
     */
    public function prepare(MultipleField $field, $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('');
        }

        $choices = [];
        foreach ($data as $index => $value) {
            $proto  = $field->getPrototype();
            $choice = $this->cloneField($proto);

            $choice->setFieldName((string) $index);
            $choice->setParent($field);

            foreach ($choice->getChildren() as $child) {
                $child->setParent($choice);
            }

            $choices[] = $choice;
        }

        $field->setChildren($choices);

        $field->setConstraints([]);
        $field->setApply(null);
        $field->setUnapply(null);
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

        if ($apply = $field->getApply()) {
            $apply = \Closure::bind($apply, $clone);
            $clone->setApply($apply);
        }

        if ($unapply = $clone->getUnapply()) {
            $unapply = \Closure::bind($unapply, $clone);
            $clone->setUnapply($unapply);
        }

        $clone->setChildren(array_map(function ($child) {
            return $this->cloneField($child);
        }, $field->getChildren()));

        $clone->setConstraints(array_map(function ($constraint) {
            return clone $constraint;
        }, $field->getConstraints()));

        return $clone;
    }
}
