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
     * @param Field   $field The field object
     * @param boolean $fst   This flag is used to find out which field was 
     *                       called first.
     *
     * @return string
     */
    public function getName(Field $field, $fst = true)
    {
        $parent = $field->getParent();

        if (null !== $parent && '' !== $parent->getName(false)) {
            return $parent->getName(false) . '[' . $field->getInternalName() . ']';
        }

        if ($fst && $field->hasOption('prototype')) {
            return $field->getInternalName() . '[]';
        }

        return $field->getInternalName();
    }

    /**
     * An alias for `getName` method.
     *
     * @param Field $field The field object
     *
     * @return string
     */
    public function name(Field $field)
    {
        return $this->getName($field);
    }
}
