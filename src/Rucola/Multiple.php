<?php

namespace Rucola;

use Rucola\Type\TypeInterface;
use Rucola\Type\MultipleType;
use Rucola\Type\FormType;

trait Multiple
{
    public function multiple($typeOfField)
    {
        if ($typeOfField instanceof TypeInterface) {
            return new MultipleType($typeOfField);
        }

        $dataMapper = $this->dataMapper;

        if ($typeOfField instanceof Field) {
            $choices = new Field('choices', new FormType());
            $choices->setMultiple(true);
            $typeOfField->setParent($choices);
            $choices->setPrototype($typeOfField);

            $choices->setApply(function () use ($dataMapper, $choices) {
                return $dataMapper->fieldToArray($choices);
            });

            return $choices;
        }

        throw new \InvalidArgumentException('You must pass an instance of TypeInterface of Field to multiple method.');
    }
}
