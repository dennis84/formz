<?php

namespace Rucula\Builder;

use Rucula\Field;
use Rucula\Type\FormType;

abstract class AbstractBuilder
{
    protected function buildFieldTree(array $fields)
    {
        $field = new Field('root', new FormType());
        $field->setRoot(true);

        foreach ($fields as $name => $typeOrField) {
            if ($typeOrField instanceof Field) {
                $typeOrField->setName($name);
                $typeOrField->setParent($field);
                $field->addChild($typeOrField);

                continue;
            }

            $next = new Field($name, $typeOrField);
            $next->setParent($field);
            $field->addChild($next);
        }

        return $field;
    }
}
