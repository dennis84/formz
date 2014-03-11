<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\Event;
use Formz\Events;
use Formz\ExtensionInterface;

/**
 * Optional.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Optional implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function initialize(Field $field)
    {
    }

    /**
     * Makes this field to an optional.
     *
     * @param Field $field The field object
     *
     * @return Field
     */
    public function optional(Field $field)
    {
        $disp = $field->getDispatcher();
        $disp->addListener(Events::BEFORE_TRANSFORM, function (Event $event) {
            if (null === $event->getInput()) {
                $event->setData(null);
            }
        });

        return $field;
    }
}
