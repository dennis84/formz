<?php

namespace Formz\Extension;

use Formz\Field;
use Formz\ExtensionInterface;
use Formz\TransformerInterface;

/**
 * This extension provides a simpler api to add a transformer.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Transform implements ExtensionInterface
{
    /**
     * Adds a transformer.
     *
     * @param Field                $field       The field object
     * @param TransformerInterface $transformer The transformer
     */
    public function transform(Field $field, TransformerInterface $transformer)
    {
        $field->addTransformer($transformer);
    }
}
