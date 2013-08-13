<?php

namespace Formz\Extension;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Formz\Field;
use Formz\Event;
use Formz\Events;

/**
 * MultipleEventSubscriber.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class MultipleEventSubscriber implements EventSubscriberInterface
{
    /**
     * Prepares the field on BIND event.
     *
     * @param Event $event The field event
     */
    public function bind(Event $event)
    {
        if (null === $event->getInput()) {
            $event->setInput([]);
        }

        $this->prepareMultipleField($event->getField(), $event->getInput());
    }

    /**
     * Prepares the field on FILL event.
     *
     * @parma Event $event The field event
     */
    public function fill(Event $event)
    {
        $this->prepareMultipleField($event->getField(), $event->getData());
    }

    /**
     * Resizes the field by input data.
     *
     * @param Event $event The field event
     */
    public function resize(Event $event)
    {
        $input = $event->getinput();
        foreach ($event->getField()->getChildren() as $child) {
            if (!isset($input[$child->getFieldName()])) {
                $event->getField()->removeChild($child->getFieldName());
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::BIND => [['bind'], ['resize']],
            Events::FILL => 'fill',
        ];
    }

    /**
     * Prepares the current field if multiple is true.
     *
     * @param Field $field The field object
     * @param mixed $data  The data
     */
    protected function prepareMultipleField(Field $field, $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('');
        }

        $choices = [];
        foreach ($data as $index => $value) {
            $choice = $this->cloneField($field);
            $choice->setFieldName((string) $index);
            $choice->setParent($field);

            foreach ($choice->getChildren() as $child) {
                $child->setParent($choice);
            }

            if ($apply = $choice->getApply()) {
                $apply = \Closure::bind($apply, $choice);
                $choice->setApply($apply);
            }

            if ($unapply = $choice->getUnapply()) {
                $unapply = \Closure::bind($unapply, $choice);
                $choice->setUnapply($unapply);
            }

            $choices[] = $choice;
        }

        $field->setChildren($choices);

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

        $dispatcher = $clone->getDispatcher();
        $dispatcher->removeListener(Events::BIND, [$this, 'bind']);
        $dispatcher->removeListener(Events::FILL, [$this, 'fill']);

        $clone->setChildren(array_map(function ($child) {
            return $this->cloneField($child);
        }, $field->getChildren()));

        $clone->setConstraints(array_map(function ($constraint) {
            return clone $constraint;
        }, $field->getConstraints()));

        return $clone;
    }
}
