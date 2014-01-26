<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\ExtensionInterface;

/**
 * This extension offers a simpler API to add custom constraints.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Verifying implements ExtensionInterface
{
    /**
     * Adds a constraint to the field.
     *
     * @param Field    $field   The field object
     * @param string   $message The error message
     * @param callable $check   The check method
     *
     * @return Field
     */
    public function verifying(Field $field, $message, callable $func)
    {
        $field->addConstraint(new \Formz\Constraint\Callback($message, $func));
        return $field;
    }
}
