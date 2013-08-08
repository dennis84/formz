<?php

namespace Formz\Extension;

use Formz\ExtensionInterface;
use Formz\Constraint;
use Formz\Field;
use Formz\Events;
use Formz\Event;

/**
 * The default constraints extension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Constraints implements ExtensionInterface
{
    /**
     * Check if this field is empty or not.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     */
    public function required(Field $field, $message = 'This field is required.')
    {
        $field->addConstraint(new \Formz\Constraint\Required($message));
        $disp = $field->getDispatcher();

        $disp->addListener(Events::BEFORE_TRANSFORM, function(Event $event) {
            $event->getField()->validate($event->getInput());
        });
    }

    /**
     * Checks if the field value is not empty.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     */
    public function nonEmptyText(Field $field, $message = 'This field must not be empty.')
    {
        $field->addConstraint(new \Formz\Constraint\NonEmptyText($message));
    }

    /**
     * Checks if the field value is numeric.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     */
    public function integer(Field $field, $message = 'This field must contain numeric values.')
    {
        $field->addConstraint(new \Formz\Constraint\Number($message));
        $field->transform(new \Formz\Transformer\Integer());
    }

    /**
     * Checks if the field value is numeric.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     */
    public function float(Field $field, $message = 'This field must contain numeric values.')
    {
        $field->addConstraint(new \Formz\Constraint\Number($message));
        $field->transform(new \Formz\Transformer\Float());
    }

    /**
     * Checks if the field value is boolean.
     *
     * @param Field  $field   The form field
     * @param string message The error message
     */
    public function boolean(Field $field, $message = 'This field must contain a boolean value')
    {
        $field->addConstraint(new \Formz\Constraint\Boolean($message));
        $field->transform(new \Formz\Transformer\Boolean());
    }
}
