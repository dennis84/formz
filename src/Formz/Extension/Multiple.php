<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\Event;
use Formz\Events;
use Formz\ExtensionInterface;

/**
 * Multiple extension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Multiple implements ExtensionInterface
{
    /**
     * Makes this field to a multiple.
     *
     * @param Field $field The field object
     */
    public function multiple(Field $field)
    {
        $subscriber = new MultipleEventSubscriber();
        $disp = $field->getDispatcher();
        $disp->addSubscriber($subscriber);
    }
}
