<?php

namespace Rucola;

use Rucola\Field;
use Rucola\Type\FormType;

trait Mapping
{
    public function mapping(array $fields, \Closure $apply = null, \Closure $unapply = null)
    {
        $dataMapper = $this->dataMapper;
        $root       = $this->buildFieldTree($fields);

        if (!$apply) {
            $root->setApply(function () use ($root, $dataMapper) {
                return $dataMapper->fieldToArray($root);
            });
        } else {
            $root->setApply($apply);
        }

        if (!$unapply) {
            $root->setUnapply(function () {});
        } else {
            $root->setUnapply($unapply);
        }

        return $root;
    }

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
