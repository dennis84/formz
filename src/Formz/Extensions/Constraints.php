<?php

namespace Formz\Extensions;

use Formz\ExtensionInterface;
use Formz\Constraint;
use Formz\Field;

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
        $field->addConstraint(new Constraint($message, function ($value) {
            return null !== $value || !empty($value);
        }));
    }

    /**
     * Checks if the field value is not empty.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     */
    public function nonEmptyText(Field $field, $message = 'This field must not be empty.')
    {
        $field->addConstraint(new Constraint($message, function ($value) {
            return null !== $value && '' !== $value;
        }));
    }

    /**
     * Checks if the field value is numeric.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     */
    public function integer(Field $field, $message = 'This field must contain numeric values.')
    {
        $field->addConstraint(new Constraint($message, function ($value) {
            return is_numeric($value);
        }));

        $field->addTransformer(new \Formz\Transformer\IntegerTransformer());
    }

    /**
     * Checks if the field value is numeric.
     *
     * @param Field  $field   The form field
     * @param string $message The error message
     */
    public function float(Field $field, $message = 'This field must contain numeric values.')
    {
        $field->addConstraint(new Constraint($message, function ($value) {
            return is_numeric($value);
        }));

        $field->addTransformer(new \Formz\Transformer\FloatTransformer());
    }

    /**
     * Checks if the field value is boolean.
     *
     * @param Field  $field   The form field
     * @param string message The error message
     */
    public function boolean(Field $field, $message = 'This field must contain a boolean value')
    {
        $field->addConstraint(new Constraint($message, function ($value) {
            return 'true' === $value || 'false' === $value || true === $value || false === $value;
        }));

        $field->addTransformer(new \Formz\Transformer\BooleanTransformer());
    }
}
