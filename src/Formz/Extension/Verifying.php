<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\ExtensionInterface;

/**
 * This extension provides a simpler api to add a custom constraint.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Verifying implements ExtensionInterface
{
    /**
     * Adds a constraint to the field.
     *
     * @param Field   $field   The field object
     * @param string  $message The error message
     * @param Closure $check   The check method
     *
     * @return Field
     */
    public function verifying(Field $field, $message, \Closure $func)
    {
        $field->addConstraint(new \Formz\Constraint\Callback($message, $func));
        return $field;
    }
}
