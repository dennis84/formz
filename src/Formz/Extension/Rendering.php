<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\ExtensionInterface;

/**
 * Rendering.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Rendering implements ExtensionInterface
{
    /**
     * Gets the name for form view.
     *
     * @param Field $field The field object
     *
     * @return string
     */
    public function getName(Field $field)
    {
        $parent = $field->getParent();

        if (null !== $parent && '' !== $parent->getName()) {
            return $parent->getName() . '[' . $field->getFieldName() . ']';
        }

        return $field->getFieldName();
    }
}
