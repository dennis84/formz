<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\Event;
use Formz\Events;
use Formz\ExtensionInterface;

class Optional implements ExtensionInterface
{
    public function optional(Field $field)
    {
        $disp = $field->getDispatcher();
        $disp->addListener(Events::BEFORE_TRANSFORM, function(Event $event) {
            if (null === $event->getInput()) {
                $event->setData(null);
            }
        });
    }
}
