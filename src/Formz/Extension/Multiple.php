<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\Event;
use Formz\Events;
use Formz\ExtensionInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
     *
     * @return Field
     */
    public function multiple(Field $proto)
    {
        $resizer = new MultipleResizeListener();

        $name  = $proto->getFieldName();
        $disp  = new EventDispatcher();
        $field = new MultipleField($name, $disp, $proto->getExtensions());
        $field->setPrototype($proto);

        $disp->addListener(Events::BIND, [ $resizer, 'bind' ]);
        $disp->addListener(Events::FILL, [ $resizer, 'fill' ]);

        return $field;
    }
}
