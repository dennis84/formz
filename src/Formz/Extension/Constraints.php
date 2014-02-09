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
     *
     * @return Field
     */
    public function required(Field $field, $message = 'formz.error.required')
    {
        $field->addConstraint(new \Formz\Constraint\Required($message));
        $disp = $field->getDispatcher();

        $disp->addListener(Events::BEFORE_TRANSFORM, function (Event $event) {
            $event->getField()->validate($event->getInput());
        });

        return $field;
    }

    /**
     * Checks if the field value is not empty.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     *
     * @return Field
     */
    public function nonEmptyText(Field $field, $message = 'formz.error.non_empty_text')
    {
        $field->addConstraint(new \Formz\Constraint\NonEmptyText($message));
        return $field;
    }

    /**
     * Checks if the field value is numeric and convert it to an integer.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     *
     * @return Field
     */
    public function integer(Field $field, $message = 'formz.error.integer')
    {
        $field->addConstraint(new \Formz\Constraint\Number($message));
        $field->transform(new \Formz\Transformer\Integer());
        return $field;
    }

    /**
     * Checks if the field value is numeric and convert it to a float.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     *
     * @return Field
     */
    public function float(Field $field, $message = 'formz.error.float')
    {
        $field->addConstraint(new \Formz\Constraint\Number($message));
        $field->transform(new \Formz\Transformer\Float());
        return $field;
    }

    /**
     * Checks if the field value is boolean.
     *
     * @param Field $field The form field
     * @param string message The error message
     *
     * @return Field
     */
    public function boolean(Field $field, $message = 'formz.error.boolean')
    {
        $field->addConstraint(new \Formz\Constraint\Boolean($message));
        $field->transform(new \Formz\Transformer\Boolean());
        return $field;
    }
}
