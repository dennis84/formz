<?php

namespace Formz;

/**
 * ExtensionInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
interface ExtensionInterface
{
    /**
     * This method is called when a new field is created.
     *
     * @param Field $field The field object
     */
    function initialize(Field $field);
}
