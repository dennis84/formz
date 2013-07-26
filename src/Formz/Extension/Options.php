<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\Error;
use Formz\Event;
use Formz\Events;
use Formz\ExtensionInterface;

/**
 * Options extension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Options implements ExtensionInterface
{
    /**
     * Allows to define options which must match with the incoming data.
     *
     * $builder->field('tag')->options([ 'a', 'b', 'c' ]);
     *
     * @param Field   $field   The field object
     * @param mixed[] $options The array of options
     * @param string  $message The error message
     */
    public function options(Field $field, array $options = [], $message = '')
    {
        $field->getDispatcher()->addListener(Events::BIND, function (Event $event) use ($options, $message) {
            if ($event->getField()->isMultiple()) {
                $this->checkMultipleField($event, $options, $message);
            } else {
                $this->checkSingleField($event, $options, $message);
            }
        });
    }

    /**
     * Checks if the data of a single field matches to the given options.
     *
     * @param Event   $event   The event object
     * @param mixed[] $options The array of options
     * @param string  $message The error message
     */
    private function checkSingleField(Event $event, array $options, $message)
    {
        if (!in_array($event->getData(), $options)) {
            $event->getField()->addError(new Error(
                $event->getField()->getFieldName(),
                $message
            ));
        }
    }
 
    /**
     * Checks if the data of a multiple field matches to the given options.
     *
     * @param Event   $event   The event object
     * @param mixed[] $options The array of options
     * @param string  $message The error message
     */
    private function checkMultipleField(Event $event, array $options, $message)
    {
        if (count(array_diff($event->getData(), $options)) > 0) {
            $event->getField()->addError(new Error(
                $event->getField()->getFieldName(),
                $message
            ));
        }
    }
}
